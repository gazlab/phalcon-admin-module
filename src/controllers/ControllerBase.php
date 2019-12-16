<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    // private $_area = 'public';

    // public function setArea($area)
    // {
    //     $_area = $area;
    // }

    // public function getArea()
    // {
    //     return $this->_area;
    // }

    public $userSession;

    public function initialize()
    {
        $this->tag->setTitle('Gazlab Admin');

        if ($this->session->has('uId')) {
            $this->userSession = User::findFirst(["id = ?0", 'bind' => [$this->session->get('uId')], 'columns' => ['username', 'avatar']]);
            $this->view->setVars([
                'userSession' => $this->userSession
            ]);
            $this->view->setTemplateBefore('private');
        }

        $this->breadcrumbs->add('Home', $this->url->get());
    }
}
