<?php

namespace Gazlab\Admin\Controllers;

class ControllerBase extends \Phalcon\Mvc\Controller
{ 
    public function initialize()
    {
        $this->tag->setTitle('Gazlab Admin');

        $this->view->setTemplateBefore('private');
    }
}
