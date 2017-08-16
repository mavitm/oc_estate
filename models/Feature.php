<?php namespace Mavitm\Estate\Models;

use Model;

/**
 * Model
 */
class Feature extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $timestamps = true;

    public $table = 'mavitm_estate_features';
    public $implement   = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    public $rules = [
        'title'     => 'required',
        'slug'      => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i', 'unique:mavitm_estate_features']
    ];

    public $belongsToMany = [
        'realty' => [
            'Mavitm\Estate\Models\Realty',
            'table' => 'mavitm_estate_realty_feature',
            'order' => 'mavitm_estate_realty.updated_at desc'
        ],
];

    public $translatable = ['title'];

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
