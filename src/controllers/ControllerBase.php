<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\Permission;
use Gazlab\Admin\Models\User;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Text;

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
        $this->breadcrumbs->add('Home', $this->url->get());
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->session->has('uId')) {
            $this->userSession = User::findFirst($this->session->get('uId'));
            $this->view->setVars([
                'userSession' => $this->userSession,
                'resources' => $this->getResources()
            ]);
            $this->view->setTemplateBefore('private');
        }

        $controllerName = $dispatcher->getControllerName();
        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {
            // Get the current identity
            $identity = $this->userSession;
            // If there is no identity available the user is redirected to index/index
            if (!$identity) {
                $this->flash->notice('You don\'t have access to this module: private');
                $dispatcher->forward([
                    'controller' => 'index',
                    'action' => 'index'
                ]);
                return false;
            }
            // Check if the user have permission to the current option
            $actionName = $dispatcher->getActionName();
            if (!$this->acl->isAllowed($identity->profile->name, $controllerName, $actionName)) {
                $this->flash->notice('You don\'t have access to this module: ' . $controllerName . ':' . $actionName);
                if ($this->acl->isAllowed($identity->profile->name, $controllerName, 'index')) {
                    $dispatcher->forward([
                        'controller' => $controllerName,
                        'action' => 'index'
                    ]);
                } else {
                    // $dispatcher->forward([
                    //     'controller' => 'session',
                    //     'action' => 'profile'
                    // ]);
                }
                return false;
            }
        }
    }

    private function getResources()
    {
        $permissions = Permission::find([
            "profile_id = ?0",
            'bind' => [
                $this->userSession->profile_id
            ]
        ]);

        $resources = [];
        foreach ($permissions as $permission) {
            $className = Text::camelize($permission->resource . '-controller');
            if (class_exists($className)) {
                $source = new $className;
                if (isset($source->menu['group'])) {
                    $resources[$source->menu['group']][] = $source;
                } else {
                    array_push($resources, $source);
                }
            }
        }

        return $resources;
    }
}
