<?php

namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->getArea() === 'private') {
            return $this->response->redirect(join('/', [
                'users',
                'profile',
            ]));
        }
        $this->dispatcher->forward([
            'controller' => 'session',
            'action' => 'signIn',
        ]);
    }
}
