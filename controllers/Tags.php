<?php namespace Mavitm\Estate\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\Estate\Models\Tag;

class Tags extends Controller
{
    public $implement = ['Backend\Behaviors\ListController'];
    
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = [
        'mavitm.estate.access.realty' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Estate', 'estate', 'tags');
    }
}