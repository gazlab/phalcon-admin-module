<?php
namespace Gazlab\Admin\Controllers;

use Phalcon\Mvc\Dispatcher;

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public $area;
    public $userSession;
    public $resources;
    public $resource;

    public function getArea()
    {
        return $this->area;
    }

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->session->has('uId')) {
            $this->setArea('private');

            $this->userSession = \Gazlab\Admin\Models\Users::findFirst($this->session->get('uId'));
            $this->view->userSession = $this->userSession;

            $this->resources = $this->getResources();
            $this->view->resources = $this->resources;
            $this->view->currentResource = $this->resource;
            $this->view->setTemplateAfter($this->getArea());
        }

        $controllerName = $dispatcher->getControllerName();
        // Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {
            // Get the current identity
            $identity = $this->userSession;
            // If there is no identity available the user is redirected to index/index
            if (!is_object($identity)) {
                $this->flash->notice('You don\'t have access to this module: private');
                $dispatcher->forward([
                    'namespace' => 'Gazlab\Admin\Controllers',
                    'controller' => 'index',
                    'action' => 'index',
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
                        'action' => 'index',
                    ]);
                } else {
                    $dispatcher->forward([
                        'namespace' => 'Gazlab\Admin\Controllers',
                        'controller' => 'index',
                        'action' => 'index',
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

        $this->breadcrumbs->add('Home', $this->url->get($this->router->getModuleName()));
        $controllerName = $this->router->getControllerName();
        if (!in_array($controllerName, ['index', 'session'])) {
            $this->breadcrumbs->add($this->resource->menu['name'], $this->url->get(join('/', [$this->router->getModuleName(), $controllerName])));

            $actionName = $this->router->getActionName();
            if ($actionName !== 'index') {
                $this->breadcrumbs->add(ucwords(\Phalcon\Text::humanize($actionName)), $this->url->get(join('/', [$this->router->getModuleName(), $controllerName, $actionName])));
            }
        }

    }

    public function getResources()
    {
        $resources = [];

        $permissions = \Gazlab\Admin\Models\Permissions::find([
            "profile_id = ?0",
            'bind' => [
                $this->userSession->profile_id,
            ],
            'columns' => 'DISTINCT resource',
        ]);

        if ($permissions->count() > 0) {
            foreach ($permissions as $permission) {
                $resource = \Phalcon\Text::camelize($permission->resource . '-controller');
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

        ksort($resources, SORT_STRING);

        return $resources;
    }
}
