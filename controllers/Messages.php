<?php namespace Mavitm\Estate\Controllers;

use BackendMenu;
use Mavitm\Estate\Models\Settings;
use Backend\Classes\Controller;

class Messages extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    
    public $requiredPermissions = [
        'mavitm.estate.access.realty'
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Mavitm.Estate', 'estate', 'messages');
        $this->vars['relatySettings'] = Settings::instance();
    }
}
