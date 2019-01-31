<?php
namespace Gazlab\Admin\Controllers;

require_once APP_PATH . '/modules/frontend/controllers/ControllerBase.php';

use Gazlab\Admin\Models\Resources;

class ControllerBase extends \Imove\Modules\Frontend\Controllers\ControllerBase
{
    public function initialize()
    {
        $this->view->setTemplateBefore('private');
        $this->view->resources = $this->getResources();
        $this->view->resource = Resources::findFirst([
            "module_id = ?0 AND controller_name = ?1 AND active = 'Y'",
            'bind' => [
                $this->router->getModuleName(),
                $this->router->getControllerName(),
            ]
        ]);

        parent::initialize();
    }

    private function getResources($parent_id = null)
    {
        $resources = Resources::find([
            "active = 'Y'" . (!is_null($parent_id) ? " AND parent = {$parent_id}": " AND parent IS NULL"),
            'order' => 'order_position',
        ]);

        $allResource = [];
        if (count($resources) === 0){
            return $allResource;
        }

        $resources = $resources->toArray();
        foreach ($resources as $resource){
            $childs = $this->getResources($resource['id']);
            if (count($childs) > 0){
                $resource['childs'] = $childs;
            }
            array_push($allResource, $resource);
        }

        return $allResource;
    }
}
