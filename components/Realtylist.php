<?php namespace Mavitm\Estate\Components;
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/

use October\Rain\Exception\ApplicationException;
use Str;
use Lang;
use Redirect;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Mavitm\Estate\Models\Realty;
use Mavitm\Estate\Models\Category;
use Mavitm\Estate\Models\Settings;
use Illuminate\Support\Facades\Input;

class Realtylist extends ComponentBase
{
    public  $data,
            $categoryPage,
            $detailPage,
            $searchPage,

            $pageParam,
            $itemsPerPage,

            $categoryID,

            $colLg = 3, $colMd = 4, $colSm = 6, $colXs = 12;

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.estate::lang.components.listName',
            'description' => 'mavitm.estate::lang.components.listNameDesc'
        ];
    }

    public function defineProperties()
    {
        $settings             = Settings::instance();

        return [
            'pageNumber' => [
                'title'             => 'mavitm.estate::lang.components.pagination',
                'description'       => 'mavitm.estate::lang.components.pagination_description',
                'type'              => 'string',
                'default'           => '{{ :page }}',
            ],
            'categoryFilter' => [
                'title'             => 'mavitm.estate::lang.components.categoryFilter',
                'description'       => 'mavitm.estate::lang.components.filter_description',
                'type'              => 'string',
                'default'           => '{{ :slug }}'
            ],
            'itemsPerPage' => [
                'title'             => 'mavitm.estate::lang.components.items_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'mavitm.estate::lang.components.items_per_page_validation',
                'default'           => 15,
            ],
            'categoryPage' => [
                'title'             => 'mavitm.estate::lang.components.items_category',
                'type'              => 'dropdown',
                'default'           => $settings->category_page,
                'group'             => 'Links',
            ],
            'detailPage' => [
                'title'             => 'mavitm.estate::lang.components.items_post',
                'type'              => 'dropdown',
                'default'           => $settings->detail_page,
                'group'             => 'Links',
                'options'           => $this->getCategoryPageOptions()
            ],
            'searchPage' => [
                'title'             => 'mavitm.estate::lang.components.items_search',
                'type'              => 'dropdown',
                'default'           => $settings->search_page,
                'group'             => 'Links',
                'options'           => $this->getCategoryPageOptions()
            ],
            'colLg' => [
                'title'             => 'col-lg-?',
                'description'       => 'mavitm.estate::lang.components.column',
                'type'              => 'string',
                'default'           => 3,
                'group'             => 'Column',
            ],
            'colMd' => [
                'title'             => 'col-md-?',
                'description'       => 'mavitm.estate::lang.components.column',
                'type'              => 'string',
                'default'           => 4,
                'group'             => 'Column',
            ],
            'colSm' => [
                'title'             => 'col-sm-?',
                'description'       => 'mavitm.estate::lang.components.column',
                'type'              => 'string',
                'default'           => 6,
                'group'             => 'Column',
            ],
            'colXs' => [
                'title'             => 'col-xs-?',
                'description'       => 'mavitm.estate::lang.components.column',
                'type'              => 'string',
                'default'           => 12,
                'group'             => 'Column',
            ],


        ];
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function onRun()
    {
        $this->prepareVars();
        $settings                   = Settings::instance();
        $this->data                 = $this->page['data']         = $this->loadList();

        $settings->_imported($this);
    }

    public function prepareVars()
    {
        $settings                   = Settings::instance();

        $this->pageParam            = $this->page['pageParam']    = $this->paramName('pageNumber');
        $this->itemsPerPage         = $this->page['itemsPerPage'] = $this->property('itemsPerPage');

        if($this->property("categoryFilter", null))
        {
            $this->prepareCategory();
        }

        $this->page['colLg'] = $this->colLg;
        $this->page['colMd'] = $this->colMd;
        $this->page['colSm'] = $this->colSm;
        $this->page['colXs'] = $this->colXs;

        if(intval($this->property('colLg'))){
            $this->colLg = $this->page['colLg'] = intval($this->property('colLg'));
        }
        if(intval($this->property('colMd'))){
            $this->colMd = $this->page['colMd'] = intval($this->property('colMd'));
        }
        if(intval($this->property('colSm'))){
            $this->colSm = $this->page['colSm'] = intval($this->property('colSm'));
        }
        if(intval($this->property('colXs'))){
            $this->colXs = $this->page['colXs'] = intval($this->property('colXs'));
        }

        $this->currency             = $this->page['currency']     = $settings->currency;
        $this->categoryPage         = $this->page['categoryPage'] = $this->property('categoryPage');
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
        $this->searchPage           = $this->page['searchPage']   = $this->property('searchPage');
        $this->status               = $this->page['status']       = (new Realty())->getStatusOptions();

    }

    protected function prepareCategory()
    {
        $category = $this->property("categoryFilter");
        if(!is_numeric($category))
        {
            $categoryRow = Category::where("slug", "=", $category)->first();
        }
        else
        {
            $categoryRow = Category::where("id", "=", $category)->first();
        }
        if(!empty($categoryRow->id))
        {
            $this->page['category'] = $categoryRow;
            $this->categoryID       = $this->page['categoryID'] = $categoryRow->id;
        }
        else
        {
            $this->categoryID       = $this->page['categoryID'] = null;
        }
    }

    public function loadList()
    {
        $param          =[
            'page'          => $this->property("pageNumber",1),
            'perPage'       => $this->itemsPerPage,
            'sort'          => 'created_at',
            'order'         => 'desc',
            'category'      => Input::get('category', $this->categoryID),
            'status'        => Input::get('status', null),
            'tags'          => Input::get('tags', null),
            'price'         => Input::get('price', null),
            'address'       => Input::get('address', null)
        ];
        $items          = Realty::ListFrontEnd($param);

        $items->each(function($item) {
            $item->setUrl($this->detailPage, $this->controller);
        });

        return $items;
    }

}