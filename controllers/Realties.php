<?php namespace Mavitm\Estate\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Mavitm\Estate\Models\Realty;

class Realties extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend.Behaviors.RelationController'
    ];

    public $listConfig      = 'config_list.yaml';
    public $formConfig      = 'config_form.yaml';
    public $relationConfig  = 'config_relation.yaml';

    public $requiredPermissions = [
        'mavitm.estate.access.realty' 
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Mavitm.Estate', 'estate', 'realty');
    }

    public function formBeforeCreate($model)
    {
        $model->user_id = $this->user->id;
    }

    public function create()
    {
        $this->addCss('/plugins/mavitm/estate/assets/css/backend.css');
        return $this->asExtension('FormController')->create();
    }

    public function update($recordId = null)
    {
        $this->addCss('/plugins/mavitm/estate/assets/css/backend.css');
        return $this->asExtension('FormController')->update($recordId);
    }

    public function index_onShow()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $realtyId) {
                if ((!$realty = Realty::find($realtyId)))
                    continue;

                $realty->published = 1;
                $realty->save();
            }

            Flash::success('Successfully showed those.');
        }

        return $this->listRefresh();
    }

    public function index_onHide()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $realtyId) {
                if ((!$realty = Realty::find($realtyId)) || !$realty->canEdit($this->user))
                    continue;

                $realty->published = 0;
                $realty->save();
            }

            Flash::success('Successfully hidden those.');
        }

        return $this->listRefresh();
    }


}