<?php

namespace Gazlab\Admin\Models;

use Phalcon\Mvc\Model\Behavior\Timestampable;

class ModelBase extends \Phalcon\Mvc\Model
{
    public function getSource()
    {
        $class = str_replace('Gazlab\Admin\Models\\', '', get_class($this));
        return 'ga_' . strtolower($class);
    }

    public function initialize()
    {
        $this->addBehavior(
            new Timestampable(
                [
                    'beforeUpdate' => [
                        'field'  => 'updated_at',
                        'format' => 'Y-m-d H:i:s',
                    ]
                ]
            )
        );
    }
}
