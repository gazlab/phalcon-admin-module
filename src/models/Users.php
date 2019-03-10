<?php
namespace Gazlab\Admin\Models;

class Users extends ModelBase
{
    public function initialize()
    {
        $this->belongsTo('profile_id', 'Gazlab\Admin\Models\Profiles', 'id', ['alias' => 'Profile']);
    }
}
