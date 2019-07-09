<?php namespace Mavitm\Estate\Models;

use Model;

/**
 * Message Model
 */
class Message extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
    * @var mixed
    */
    public $timestamps = true;

    /**
    * @var string
    */
    public $table = 'mavitm_estate_messages';

    /**
    * @var array
    */
    protected $guarded = ['id'];
   
    /**
    * @var array
    */
    public $rules = [];

    /**
    * @var array
    */
    public $translatable = ['message']; 

    ############################################################################################################
    # PERMISSIONS
    ############################################################################################################

    public function canEdit(User $user)
    {
        return ($this->user_id == $user->id) || $user->hasAnyAccess(['mavitm.estate.access.realty']);
    }

    public $belongsTo = [
        'realty' => [ 'Mavitm\Estate\Models\Realty' ]
    ];
}
