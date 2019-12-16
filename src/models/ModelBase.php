<?php

namespace Gazlab\Admin\Models;

class ModelBase extends \Phalcon\Mvc\Model
{
    public function getSource()
    {
        $class = str_replace('Gazlab\Admin\Models\\', '', get_class($this));
        return 'ga_' . strtolower($class);
    }
}
