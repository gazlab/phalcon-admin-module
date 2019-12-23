<?php

namespace Gazlab\Admin\Models;

class Profile extends ModelBase
{
    public function initialize()
    {
        $this->hasMany('id', 'Gazlab\Admin\Models\Permission', 'profile_id', ['alias' => 'Permissions']);
    }
}
