<?php

namespace Gazlab\Admin\Models;

class Permission extends ModelBase
{
    public function initialize()
    {
        $this->setSource('ga_permissions');
        parent::initialize();
    }
}
