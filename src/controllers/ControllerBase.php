<?php
namespace Gazlab\Admin\Controllers;

require_once APP_PATH . '/modules/frontend/controllers/ControllerBase.php';

use Gazlab\Admin\Models\Resources;
use Phalcon\Mvc\Model\Resultset;

class ControllerBase extends \Imove\Modules\Frontend\Controllers\ControllerBase
{
    public $resources;

    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        $this->view->resources = $this->resources;
        $resource = Resources::findFirst([
            "module_id = ?0 AND controller_name = ?1 AND active = 'Y'",
            'bind' => [
                $this->router->getModuleName(),
                $this->router->getControllerName(),
            ],
        ]);
        $this->view->resource = $resource;

        // Breadcrums
        $this->breadcrumbs->add('Home', $this->url->get($this->router->getModuleName()));
        if ($this->router->getControllerName() !== 'index') {
            $this->breadcrumbs->add($resource->name, $this->url->get($this->router->getModuleName() . '/' . $resource->controller_name));
        }
        if ($this->router->getActionName() !== 'index') {
            $this->breadcrumbs->add(ucwords(\Phalcon\Text::humanize($this->router->getActionName())), '#');
        }

        parent::initialize();
    }

    private function getResources($parent_id = null)
    {
        $profiles = [];
        foreach ($this->userProfiles as $profile) {
            array_push($profiles, $profile->id);
        }

        $resources = $this->modelsManager->createBuilder()
            ->columns(["r.*"])
            ->from(['r' => 'Resources'])
            ->join('Permissions', 'p.resource_id = r.id', 'p')
            ->where("module_id = ?0 AND active = 'Y'" . (!is_null($parent_id) ? " AND parent = {$parent_id}" : " AND parent IS NULL"), [
                $this->router->getModuleName(),
            ])
            ->inWhere('p.profile_id', $profiles)
            ->orderBy('r.order_position')
            ->groupBy('r.id')
            ->getQuery()
            ->execute()
            ->setHydrateMode(Resultset::HYDRATE_ARRAYS);

        $allResource = [];
        if (count($resources) === 0) {
            return $allResource;
        }

        foreach ($resources as $resource) {
            $childs = $this->getResources($resource['id']);
            if (count($childs) > 0) {
                $resource['childs'] = $childs;
            }
            array_push($allResource, $resource);
        }

        return $allResource;
    }

    public function beforeExecuteRoute()
    {
        if (!$this->session->has('uId')) {
            return $this->response->redirect('?redirect=' . $this->router->getRewriteUri());
        }

        parent::beforeExecuteRoute();

        $this->resources = $this->getResources();

        if ($this->router->getControllerName() === 'index') {
            foreach ($this->resources as $resource) {
                if (!is_null($resource['controller_name'])) {
                    return $this->response->redirect($this->router->getModuleName() . '/' . $resource['controller_name']);
                }
            }
        }
    }
}
