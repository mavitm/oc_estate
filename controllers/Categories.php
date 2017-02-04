<?php namespace Mavitm\Estate\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Categories extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController'
    ];
    
    public $listConfig      = 'config_list.yaml';
    public $formConfig      = 'config_form.yaml';
    public $reorderConfig   = 'config_reorder.yaml';

    public $requiredPermissions = [
        'mavitm.estate.access.realty' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Estate', 'estate', 'category');
    }
}