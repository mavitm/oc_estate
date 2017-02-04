<?php namespace Mavitm\Estate\Components;
/**
*@Author Mavitm
*@url http://www.mavitm.com
*/
use Str;
use Lang;
use Redirect;
use Cms\Classes\ComponentBase;
use Mavitm\Estate\Models\Realty;
use Mavitm\Estate\Models\Category;
use Mavitm\Estate\Models\Settings;

class SearchForm extends ComponentBase
{
    public
        /**
         * Category page name
         * @var string
         */
            $categoryPage = '',
        /**
         * Eloquent\Collection Object
         * @var null
         */
            $categories = null,
        /**
         * Buy, Rent, Sold
         * @var array
         */
            $status     = [],

            $langs      = null;


    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.estate::lang.components.searchForm',
            'description' => 'mavitm.estate::lang.components.searchFormDesc'
        ];
    }

    public function init()
    {
        parent::init();

        $settings                   = Settings::instance();
        $this->page['categoryPage'] =
        $this->categoryPage         = $settings->category_page;

        $this->page['status']       =
        $this->status               = (new Realty())->getStatusOptions();

        $this->page['categories']   =
        $this->categories           = Category::orderBy("sort_order","asc")->get();

        $this->langs                = new \stdClass();

        $this->page['placeholder']  =
        $this->langs->placeholder   = Lang::get('mavitm.estate::lang.components.searchPlaceHolder');

        $settings->_imported($this);
    }

}