<?php

namespace Gazlab\Admin\Models;

class User extends ModelBase
{
    public function initialize()
    {
        $this->setSource('ga_users');
        parent::initialize();
    }
}
