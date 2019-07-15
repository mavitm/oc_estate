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
use Mavitm\Estate\Models\Settings;
use Illuminate\Support\Facades\Input;

class NewestList extends ComponentBase
{
    public  $data,
            $detailPage,
            $pageParam,
            $itemsPerPage,
            $colLg = 3, $colMd = 4, $colSm = 6, $colXs = 12;

    public function componentDetails()
    {
        return [
            'name'        => 'mavitm.estate::lang.components.newestName',
            'description' => 'mavitm.estate::lang.components.newestNameDesc'
        ];
    }

    public function defineProperties()
    {
        $settings             = Settings::instance();

        return [
            'itemsPerPage' => [
                'title'             => 'mavitm.estate::lang.components.items_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'mavitm.estate::lang.components.items_per_page_validation',
                'default'           => 4,
            ],
            'detailPage' => [
                'title'             => 'mavitm.estate::lang.components.items_post',
                'type'              => 'dropdown',
                'default'           => $settings->detail_page,
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

        $this->itemsPerPage         = $this->page['itemsPerPage'] = $this->property('itemsPerPage');

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
        $this->detailPage           = $this->page['detailPage']   = $this->property('detailPage');
        $this->status               = $this->page['status']       = (new Realty())->getStatusOptions();

    }

    public function loadList()
    {
        $param          =[
            'perPage'       => $this->itemsPerPage,
            'sort'          => 'created_at',
            'order'         => 'desc'
        ];

        $items          = Realty::ListFrontEnd($param);

        $items->each(function($item) {
            $item->setUrl($this->detailPage, $this->controller);
        });

        return $items;
    }

}