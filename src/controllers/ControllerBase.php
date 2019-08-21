<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public $userSession;

    public function initialize()
    {
        $this->tag->setTitle($this->config->gazlab->title);

        if ($this->session->has('uId')) {
            $this->view->setTemplateAfter('private');

            $user = User::findFirst($this->session->get('uId'));
            $this->userSession = $user;
        }

        $this->view->setVars([
            'userSession' => $this->userSession
        ]);
    }

    public function beforeExecuteRoute($dispatcher)
    {
        if (!$this->session->has('uId')) {
            if ($dispatcher->getControllerName() != 'session') {
                $dispatcher->forward([
                    'controller' => 'session',
                    'action' => 'signIn',
                ]);
                return false;
            }
        }
    }
}
