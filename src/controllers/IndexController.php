<?php

namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->getArea() === 'private') {
            if (isset($this->resources[0])) {
                return $this->response->redirect(join('/', [
                    $this->resources[0]->menu[0]
                ]));
            }
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
