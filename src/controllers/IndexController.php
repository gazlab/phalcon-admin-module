<?php
namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->getArea() === 'private') {
            return $this->response->redirect(join('/', [
                $this->router->getModuleName(),
                $this->resources[0]->menu[0],
            ]));
        }
        $this->dispatcher->forward([
            'controller' => 'session',
            'action' => 'signIn',
        ]);
    }
}
