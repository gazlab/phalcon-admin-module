<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\Permission;
use Gazlab\Admin\Models\User;
use Phalcon\Mvc\Dispatcher;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public $userSession, $area, $menu, $resource, $resources;

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->session->has('uId')) {
            $this->setArea('private');

            $user = User::findFirst($this->session->get('uId'));
            $this->userSession = $user;

            $this->resources = $this->getResources();

            $this->view->setVars([
                'userSession' => $this->userSession,
                'resources' => $this->resources,
                'currentResource' => $this->resource
            ]);
            $this->view->setTemplateAfter($this->getArea());
        }

        $controllerName = $dispatcher->getControllerName();
        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {
            // Get the current identity
            $identity = $this->userSession;
            // $identity = $this->auth->getIdentity();
            // If there is no identity available the user is redirected to index/index
            if (!is_object($identity)) {
                $this->flash->notice('You don\'t have access to this module: private');
                // $this->response->redirect();
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
                    $dispatcher->forward([
                        'controller' => 'users',
                        'action' => 'profile'
                    ]);
                }
                return false;
            }
        }
    }

    public function initialize()
    {
        $this->tag->setTitleSeparator(' | ');
        $this->tag->setTitle($this->config->gazlab->title);
        if (isset($this->resource->menu['name'])) {
            $this->tag->prependTitle($this->resource->menu['name']);
        }

        if (!is_null($this->resource)) {
            $this->breadcrumbs->add('Home', $this->url->get());
            if ($this->router->getControllerName() !== 'index') {
                $this->breadcrumbs->add($this->resource->menu['name'], $this->url->get($this->resource->menu[0]));
                if ($this->router->getActionName() !== 'index') {
                    $this->breadcrumbs->add(ucwords(\Phalcon\Text::humanize(\Phalcon\Text::uncamelize($this->router->getActionName(), '-'))), $this->url->get($this->resource->menu[0] . '/' . $this->router->getActionName()));
                }
            }
        }
    }

    public function getResources()
    {
        $resources['managements'] = [];

        array_push($resources['managements'], new UsersController());

        if (isset($resources[0]) && $this->router->getControllerName() === $resources[0]->menu[0]) {
            $this->resource = $resources[0];
        }
        if (isset($resources['managements'][0]) && $this->router->getControllerName() === $resources['managements'][0]->menu[0]) {
            $this->resource = $resources['managements'][0];
        }

        $permissions = Permission::find([
            "profile_id = ?0",
            'bind' => [
                $this->userSession->profile_id
            ],
            'columns' => [
                "DISTINCT resource"
            ]
        ]);

        if ($permissions->count() > 0) {
            foreach ($permissions as $permission) {
                $resource = \Phalcon\Text::camelize($permission->resource . '-controller');
                if (class_exists($resource)) {
                    $resource = new $resource();
                    $resource->menu['name'] = isset($resource->menu['name']) ? $resource->menu['name'] : ucwords(\Phalcon\Text::humanize($resource->menu[0]));

                    if ($this->router->getControllerName() === $resource->menu[0]) {
                        $this->resource = $resource;
                    }

                    if (isset($resource->menu['group'])) {
                        $resources[$resource->menu['group']][] = $resource;
                    } else {
                        array_push($resources, $resource);
                    }
                }
            }
        }

        ksort($resources, SORT_STRING);

        return $resources;
    }
}
