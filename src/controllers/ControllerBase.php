<?php

namespace Gazlab\Admin\Controllers;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public function initialize()
    {
        $this->tag->setTitle($this->config->gazlab->title);
    }

    public function beforeExecuteRoute($dispatcher)
    {
        if (!$this->session->has('uId')) {
            if ($dispatcher->getControllerName() != 'session') {
                $this->dispatcher->forward([
                    'controller' => 'session',
                    'action' => 'signIn',
                ]);
                return false;
            }
            return;
        }

        $this->view->setTemplateAfter('private');
    }
}
