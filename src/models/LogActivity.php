<?php

namespace Gazlab\Admin\Models;

class LogActivity extends ModelBase
{
    public function initialize()
    {
        $this->setSource('ga_log_activities');
        // parent::initialize();
    }
}