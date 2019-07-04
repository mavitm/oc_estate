<?php
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/

namespace Mavitm\Estate\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Estate\Models\Realty;
use Mavitm\Estate\Models\Settings;
use Mavitm\Estate\Models\Category as CategoryModel;


class Category extends ComponentBase{

    /**
     * menu kybele items
     * @var
     */
    public $childs;

    /**
     * active item selected
     * @var
     */
    public  $categoryPage,
            $countSub;


    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.estate::lang.components.catehoryName',
            'description' => 'mavitm.estate::lang.components.categoryDesc'
        ];
    }

    public function defineProperties()
    {
        $settings             = Settings::instance();
        return [
            'categoryPage' => [
                'title'       => 'mavitm.estate::lang.components.items_category',
                'type'        => 'dropdown',
                'default'     => $settings->category_page,
            ],
            'countSub' => [
                'title'       => 'mavitm.estate::lang.settings.showCount',
                'description' => false,
                'default'     => ($settings->show_category_count ? 1:0),
                'type'        => 'checkbox'
            ],
        ];
    }


    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->countSub         = $this->page['countSub']       = $this->property('countSub');
        $this->categoryPage     = $this->page['categoryPage']   = $this->property('categoryPage');
        $this->childs           = $this->page['childs']         = $this->linkCategories(CategoryModel::get());

    }

    protected function linkCategories($categories)
    {
        return $categories->each(function($category) {
            $category->setUrl($this->categoryPage, $this->controller);
        });
    }

}