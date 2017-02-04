<?php namespace Mavitm\Estate\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $timestamps = true;

    public $table = 'mavitm_estate_categories';
    public $implement   = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $rules = [
        'title'     => 'required',
        'slug'      => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:mavitm_estate_categories']
    ];

    public $hasMany = [
        'realty' => ['Mavitm\Estate\Models\Realty']
    ];

    public $translatable = ['title', 'description'];

    public function afterDelete()
    {
        $this->realty()->detach();
    }

    public function setUrl($pageName, $controller)
    {
        $params = [
            'id' => $this->id,
            'slug' => $this->slug,
        ];

        return $this->url = $controller->pageUrl($pageName, $params);
    }

    public function getCountRealtyAttribute(){
        return $this->realty->count();
    }
}