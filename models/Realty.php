<?php namespace Mavitm\Estate\Models;

use Str;
use Lang;
use Model;
use Carbon\Carbon;
use Backend\Models\User;
use ValidationException;
use Mavitm\Estate\Models\Settings;
use RainLab\Translate\Models\Message;
/**
 * Model
 */
class Realty extends Model
{
    use \October\Rain\Database\Traits\Validation;

    public $table       = 'mavitm_estate_realty';
    public $implement   = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $rules = [
        'title'     => 'required',
        'slug'      => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:mavitm_estate_realty'],
        'price'     => 'required'
    ];

    public $translatable = ['title', 'excerpt', 'description'];

    public static $allowedSortingOptions = array(
        'title'         => 'Title',
        'price'         => 'Price',
        'created_at'    => 'Created',
        'updated_at'    => 'Updated',
        'sort_order'    => 'Order',
        'random'        => 'Random'
    );

    ############################################################################################################
    # RELATIONS
    ############################################################################################################

    public $belongsTo = [
        'category' => ['Mavitm\Estate\Models\Category']
    ];
    public $attachMany = [
        'images' => ['System\Models\File']
    ];
    public $belongsToMany = [
        'tags' => [
            'Mavitm\Estate\Models\Tag',
            'table' => 'mavitm_estate_realty_tag',
            'order' => 'title'
        ],
        'features' => [
            'Mavitm\Estate\Models\Feature',
            'table' => 'mavitm_estate_realty_feature',
            'order' => 'title'
        ],
    ];

    public $hasMany = [
        'properties' => [
            'Mavitm\Estate\Models\Property',
            'order' => 'sort_order',
        ],
    ];

    ############################################################################################################
    # PERMISSIONS
    ############################################################################################################

    public function canEdit(User $user)
    {
        return ($this->user_id == $user->id) || $user->hasAnyAccess(['mavitm.estate.access.realty']);
    }


    ############################################################################################################
    # GET ATTRIBUTE
    ############################################################################################################

    public function getCategoryNameAttribute(){
        if (!$this->category) {
            return;
        }
        return $this->category->title;
    }

    public function getFormatPriceAttribute(){
        return @number_format($this->price, 2, ',', '.');
    }

    public function getRealtyimagesAttribute()
    {
        foreach ($this->images as $image) {
            return '<img src="'.$image->getThumb(110, 30, ['mode' => 'crop']).'" alt="" />';
        }
    }

    public function getSrcimageAttribute($value, $w = 250, $h = 250)
    {
        foreach ($this->images as $image) {
            return $image->getThumb($w, $h, ['mode' => 'crop']);
        }
    }

    public function getStatusOptions()
    {
        /* db index => lang index */
        return [
//            0 => e(trans('mavitm.estate::lang.realty.buy')),
//            1 => e(trans('mavitm.estate::lang.realty.rent')),
//            2 => e(trans('mavitm.estate::lang.realty.sold'))

            0 => Message::get("Buy"),//_("Buy"),
            1 => Message::get("Rent"),//_("Rent"),
            2 => Message::get("Sold"),//_("Sold")

        ];
    }

    public function getTextStatusAttribute($value)
    {
        /* db index => lang index */
        return $this->getStatusOptions()[$this->status];
    }

    ############################################################################################################
    # SET ATTRIBUTE
    ############################################################################################################

    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        $params['category'] = null;

        return $this->url = $controller->pageUrl($pageName, $params);
    }


    ############################################################################################################
    # SCOPE
    ############################################################################################################

    public function scopeIsPublished($query)
    {
        return $query
            ->where('published', '=', 1)
            ->where('created_at', '<', Carbon::now());
    }

    public function scopeListFrontEnd($query, $options)
    {
        /*
         * Default options
         */
        extract(array_merge([
            'page'          => 1,
            'perPage'       => 30,
            'sort'          => 'created_at',
            'order'         => 'desc',
            'category'      => null,
            'status'        => null,
            'tags'          => null,
            'price'         => null
        ], $options));


        if($perPage > 100){
            $perPage = (
                intval(Settings::instance()->maxPerPage) ?
                    intval(Settings::instance()->maxPerPage) : 100
            );
        }

        $query->isPublished();

        if(!empty($tags)){
            
            $tags = array_map('trim', explode(",", $tags));

            $func = function($value) {
                return "%".strtolower($value)."%";
            };

            $tags = array_map($func, $tags);

            $tagsCount = count($tags);

            if ($tagsCount) {

                $query->whereHas('tags', function($q) use ($tags, $tagsCount) {

                    $q->where('title', 'like', $tags[0]);

                    if ($tagsCount > 1) {
                        for ($i = 1; $i < $tagsCount; $i++) {
                            $q->orWhere('title', 'like', $tags[$i]);
                        }
                    }
                });
            }
        }

        if(!empty($category)){
            $query->where("category_id", "=", $category);
        }

        if(!empty($status) || is_numeric($status)){
            $query->where("status", "=", $status);
        }

        if(!empty($price)){
            if(is_array($price) && count($price) == 2){
                $query->whereBetween('price', [min($price), max($price)]);
            }else{
                $query->where("price", ">=", floatval($price));
            }
        }

        if(!in_array($sort, self::$allowedSortingOptions)){
            $sort = 'created_at';
        }

        if($order != 'desc'){
            $order = 'asc';
        }

        return $query->paginate($perPage, $page);
    }

}
