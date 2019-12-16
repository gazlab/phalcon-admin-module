<?php

namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->session->has('uId')) { } else {
            $this->dispatcher->forward([
                'controller' => 'session',
                'action' => 'signIn'
            ]);
        }
    }
}
