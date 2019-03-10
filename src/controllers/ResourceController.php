<?php
namespace Gazlab\Admin\Controllers;

class ResourceController extends ControllerBase
{
    // READ
    public $columns = [];
    public function column($params)
    {
        $params['header'] = isset($params['header']) ? $params['header'] : ucwords(\Phalcon\Text::humanize($params[0]));
        array_push($this->columns, $params);
    }
    public function actions($extras = [])
    {
        $params['header'] = '';
        $params['searchable'] = false;
        $params['orderable'] = false;
        array_push($this->columns, $params);
    }
    public function indexAction()
    {
        $this->table();
        $this->view->partial($this->config->application->viewsDir . 'contents/table', ['columns' => $this->columns, 'box' => true]);
    }
}
