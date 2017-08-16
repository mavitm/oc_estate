<?php namespace Mavitm\Estate;

use Backend;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{

    public function pluginDetails()
    {
        return [
            'name'          => 'mavitm.estate::lang.plugin.name',
            'description'   => 'mavitm.estate::lang.plugin.description',
            'author'        => 'Mavitm',
            'icon'          => 'icon-building',
            'homepage'      => ''
        ];
    }

    public function registerComponents()
    {
        return [
            'Mavitm\Estate\Components\SearchForm'    => 'search_form',
            'Mavitm\Estate\Components\Realtylist'    => 'realty_list',
            'Mavitm\Estate\Components\Realtydetail'  => 'realty_detail',
            'Mavitm\Estate\Components\Category'      => 'realty_category',
        ];
    }

    public function registerPermissions()
    {
        return [
            'mavitm.estate.access.realty' => [
                'tab'   => 'mavitm.estate::lang.plugin.name',
                'label' => 'mavitm.estate::lang.plugin.name'
            ]
        ];
    }

    public function registerNavigation()
    {
        return [
            'estate' => [
                'label'         => 'mavitm.estate::lang.plugin.name',
                'url'           => Backend::url('mavitm/estate/realties'),
                'icon'          => 'icon-building',
                'permissions'   => ['mavitm.estate.access.realty'],

                'sideMenu' => [
                    'realty' => [
                        'label'         => 'mavitm.estate::lang.plugin.name',
                        'url'           => Backend::url('mavitm/estate/realties'),
                        'icon'          => 'icon-building-o',
                        'permissions'   => ['mavitm.estate.access.realty']
                    ],
                    'category' => [
                        'label'         => 'mavitm.estate::lang.plugin.category',
                        'url'           => Backend::url('mavitm/estate/categories'),
                        'icon'          => 'icon-folder',
                        'permissions'   =>['mavitm.estate.access.realty']
                    ],
                    'feature' => [
                        'label'         => 'mavitm.estate::lang.plugin.features',
                        'url'           => Backend::url('mavitm/estate/features'),
                        'icon'          => 'icon-check-square-o',
                        'permissions'   =>['mavitm.estate.access.realty']
                    ],
                    'tags' => [
                        'label'         => 'mavitm.estate::lang.tags.title',
                        'url'           => Backend::url('mavitm/estate/tags'),
                        'icon'          => 'icon-tags',
                        'permissions'   => ['mavitm.estate.access.realty']
                    ],
                    'setting' => [
                        'label'         => 'mavitm.estate::lang.settings.menuLabel',
                        'url'           => Backend::url('system/settings/update/mavitm/estate/settings'),
                        'icon'          => 'icon-cog',
                        'permissions'   => ['mavitm.estate.access.realty']
                    ]
                ]
            ]
        ];
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'         => 'mavitm.estate::lang.settings.menuLabel',
                'description'   => 'mavitm.estate::lang.settings.menuDescription',
                'category'      => 'mavitm.estate::lang.plugin.name',
                'icon'          => 'icon-cog',
                'class'         => 'Mavitm\Estate\Models\Settings',
                'order'         => 100
            ],
        ];
    }
}
