<?php namespace Mavitm\Estate\Components;
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
use Str;
use Lang;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Estate\Models\Realty;
use Mavitm\Estate\Models\Category;
use Mavitm\Estate\Models\Settings;
use Illuminate\Support\Facades\Input;
class Realtydetail extends ComponentBase
{

    public  $categoryPage,
            $item;

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.estate::lang.components.detailName',
            'description' => 'mavitm.estate::lang.components.detailDesc'
        ];
    }

    public function defineProperties()
    {
        $settings             = Settings::instance();
        return [
            'slug' => [
                'title'       => 'mavitm.estate::lang.realty.slug',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ],
            'categoryPage' => [
                'title'       => 'mavitm.estate::lang.components.items_category',
                'type'        => 'dropdown',
                'default'     => $settings->category_page,
            ],
        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $settings           = Settings::instance();
        $this->currency     = $this->page['currency']     = $settings->currency;
        $this->categoryPage = $this->page['categoryPage']   = $this->property('categoryPage');
        $this->item         = $this->page['item']           = $this->loadItem();

        $settings->_imported($this);
    }

    protected function loadItem()
    {
        $slug = $this->property('slug');
        $item = Realty::isPublished()->where('slug', $slug)->first();

        $item->category->setUrl($this->categoryPage, $this->controller);

        return $item;
    }

}