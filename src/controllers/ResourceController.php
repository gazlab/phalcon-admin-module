<?php
namespace Gazlab\Admin\Controllers;

use DataTables\DataTable;

class ResourceController extends ControllerBase
{
    // READ
    public $columns = [];
    public $queryMethod = 'builder';
    public function column($params)
    {
        $params['header'] = isset($params['header']) ? $params['header'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $params['data'] = $params[0];
        array_push($this->columns, $params);
    }
    public function actions($extras = [])
    {
        $params['header'] = '';
        $params['searchable'] = false;
        $params['orderable'] = false;
        array_push($this->columns, $params);
    }
    public function getQueryMethod()
    {
        return $this->queryMethod;
    }
    public function setQueryMethod($queryMethod)
    {
        $this->queryMethod = $queryMethod;
    }
    public function queryGetAll()
    {
        return $this->modelsManager->createBuilder()
            ->from(\Phalcon\Text::camelize($this->router->getControllerName()));
    }
    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $queryMethod = 'from' . \Phalcon\Text::camelize($this->getQueryMethod());

            $dataTables = new DataTable();
            return $dataTables->$queryMethod($this->queryGetAll())->sendResponse();
        }

        $this->table();
        $this->view->partial($this->config->application->viewsDir . 'contents/table', ['columns' => $this->columns, 'box' => true]);
    }
}
