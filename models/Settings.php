<?php namespace Mavitm\Estate\Models;
/**
 * Created by PhpStorm.
 * User: ayhan
 * Date: 02.10.2016
 * Time: 13:39
 */
use Model;
use Cms\Classes\Page;

class Settings extends Model
{
    public $implement       = ['System.Behaviors.SettingsModel'];
    public $settingsCode    = 'mavitm_estate_settings';
    public $settingsFields  = 'fields.yaml';

    private static
            $importVendor   = 0;

    /**
     * Font Awesome Currency Icons
     * @var array
     */
    public $currencyCssClass= [
        'btc'   => 'bitcoin',
        'eur'   => 'euro',
        'gbp'   => 'gbp',
        'gg'    => 'gg',
        'ils'   => 'ils',
        'inr'   => 'jnr',
        'jpy'   => 'jpy',
        'krw'   => 'krw',
        'rub'   => 'rub',
        'usd'   => 'usd',
        'try'   => 'try',
    ];

    public function getDetailPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getCurrencyOptions()
    {
        return $this->currencyCssClass;
    }

    /** This should not be here **/
    public function _imported($controller)
    {
        if(self::$importVendor > 0){
            return false;
        }
        self::$importVendor = 1;

        if($this->import_bootsrap){
            $controller->addCss('/plugins/mavitm/estate/assets/css/bootstrap.min.css');
            $controller->addJs('/plugins/mavitm/estate/assets/js/bootstrap.min.js');
        }

        if($this->import_owl){
            $controller->addCss('/plugins/mavitm/estate/assets/css/owl.carousel.css');
            $controller->addCss('/plugins/mavitm/estate/assets/css/owl.theme.css');
            $controller->addCss('/plugins/mavitm/estate/assets/css/owl.transitions.css');
            $controller->addJs('/plugins/mavitm/estate/assets/js/owl.carousel.min.js');
        }

        if($this->import_fa){
            $controller->addCss('/plugins/mavitm/estate/assets/css/font-awesome.min.css');
            $controller->addCss('/plugins/mavitm/estate/assets/css/font-awesome-v4-shims.min.css');
        }

        if($this->import_custom){
            $controller->addCss('/plugins/mavitm/estate/assets/css/style.css');
            $controller->addJs('/plugins/mavitm/estate/assets/js/mavitm.js');
        }
    }

}
