<?php namespace Mavitm\Estate\Models;

use Model;

/**
 * Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'mavitm_estate_tags';

    public $fillable = ['title'];

    /*
     * Validation
     */
    public $rules = [
        'title' => 'required|unique:mavitm_estate_tags|regex:/^[a-z0-9-_]+$/'
    ];

    public $customMessages = [
        'title.required'    => 'A tag title is required.',
        'title.unique'      => 'A tag by that title already exists.',
        'title.regex'       => 'Tags may only contain alpha-numeric characters and hyphens.'
    ];

    public $belongsToMany = [
        'realty' => [
            'Mavitm\Estate\Models\Realty',
            'table' => 'mavitm_estate_realty_tag',
            'order' => 'mavitm_estate_realty.updated_at desc'
        ],
        'items_count' => [
            'Mavitm\Estate\Models\Realty',
            'table' => 'mavitm_estate_realty_tag',
            'count' => true
        ]
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtolower($value);
    }

    public function scopeSearch($query, $tags)
    {
        if(is_array($tags)){
            $query->where("title", "like", "%".$tags[0]."%");
            for($i = 1; $i < count($tags); $i++){
                $query->orWhere("title", "like", "%".$tags[$i]."%");
            }
        }else{
            $query->where("title", "like", "%$tags%");
        }
    }

}