<?php
namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        $this->response->redirect($this->router->getModuleName() . '/modules');
    }
}
