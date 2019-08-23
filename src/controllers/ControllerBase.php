<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public $userSession;

    public function initialize()
    {
        $this->tag->setTitleSeparator(' | ');
        $this->tag->setTitle($this->config->gazlab->title);

        if ($this->session->has('uId')) {
            $this->view->setTemplateAfter('private');

            $user = User::findFirst($this->session->get('uId'));
            $this->userSession = $user;
        }

        if (!isset($this->menu['name'])) {
            $this->menu['name'] = ucwords(\Phalcon\Text::humanize($this->menu[0]));
        }

        $this->tag->prependTitle($this->menu['name']);

        $this->view->setVars([
            'userSession' => $this->userSession,
            'menu' => $this->menu
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
