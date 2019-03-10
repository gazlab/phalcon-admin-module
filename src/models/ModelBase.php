<?php
namespace Gazlab\Admin\Models;

class ModelBase extends \Phalcon\Mvc\Model
{
    public function getSource()
    {
        return 'ga_' . strtolower(str_replace('Gazlab\Admin\Models\\', '', get_class($this)));
    }
}
