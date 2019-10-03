<?php

namespace Gazlab\Admin\Models;

use Phalcon\Di;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class ModelBase extends \Phalcon\Mvc\Model
{
    public function initialize()
    {
        $this->addBehavior(
            new Timestampable(
                [
                    'beforeUpdate' => [
                        'field'  => 'updated_at',
                        'format' => 'Y-m-d H:i:s'
                    ]
                ]
            )
        );

        $config = Di::getDefault()->get('config');
        if ($config->gazlab->logActivities->status) {
            $this->addBehavior(
                new \Gazlab\LogActivities()
            );
        }
    }
}
