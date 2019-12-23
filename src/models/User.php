<?php

namespace Gazlab\Admin\Models;

class User extends ModelBase
{
    public function initialize()
    {
        $this->belongsTo('profile_id', 'Gazlab\Admin\Models\Profile', 'id', ['alias' => 'Profile']);
    }
}
