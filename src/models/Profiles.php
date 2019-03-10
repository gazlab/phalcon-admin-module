<?php
namespace Gazlab\Admin\Models;

class Profiles extends ModelBase
{
    public function initialize()
    {
        $this->hasMany('id', 'Gazlab\Admin\Models\Permissions', 'profile_id', ['alias' => 'Permissions']);
    }
}
