<?php

namespace Gazlab\Admin\Models;

class Permission extends ModelBase
{
    public $actions;

    public function getActions()
    {
        return json_decode($this->actions, true);
    }
}
