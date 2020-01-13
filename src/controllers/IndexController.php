<?php

namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->session->has('uId')) {
            if (!$this->resource) {
                $index = array_key_first($this->resources);
                if (!is_int($index)) {
                    $this->dispatcher->forward([
                        'namespace' => '',
                        'controller' => $this->resources[$index][0]->menu[0]
                    ]);
                } else {
                    $this->dispatcher->forward([
                        'namespace' => '',
                        'controller' => $this->resources[0]->menu[0]
                    ]);
                }
            }
        } else {
            $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'signIn'
            ]);
        }
    }
}
