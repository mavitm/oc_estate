<?php namespace Mavitm\Estate\Controllers;

use BackendMenu;
use Mavitm\Estate\Models\Settings;
use Backend\Classes\Controller;

class Properties extends Controller
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

        BackendMenu::setContext('Mavitm.Estate', 'estate', 'properties');
        $this->vars['relatySettings'] = Settings::instance();
    }

    public function formExtendFields($form)
    {
        $form->addFields([
            'iconcss' => [
                'label'         => 'Icon',
                'type'          => 'awesomeiconslist',
                'unicodeValue'  => false,
                'emptyOption'   => false,
                'iconTypes'     => 'solid, brands',
                'placeholder'   => "Select Icon",
                'span'          => 'auto'
            ]
        ]);
    }
}
