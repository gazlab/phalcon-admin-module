<?php

namespace Gazlab\Admin\Controllers;

use Cake\Utility\Inflector;
use DataTables\DataTable;

class ResourceController extends ControllerBase
{
    private $tableColumns = [];

    public function column($params)
    {
        if (!isset($params['header'])) {
            $params['header'] = ucwords(\Phalcon\Text::humanize($params[0]));
        }

        $params['dataTable']['data'] = $params[0];

        array_push($this->tableColumns, $params);
    }

    public function actions($actions = [])
    {
        // Edit Actions
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'update')) {
            array_push($actions, '<a href=\"' . $this->url->get(join('/', [$this->router->getControllerName(), 'update'])) . '/"+row.DT_RowId+"\" class=\"btn btn-default\" title=\"Edit\"><i class=\"fa fa-edit\"></i></a>');
        }

        $params['header'] = '';
        $params['dataTable'] = ['data' => 'DT_Actions', 'searchable' => false, 'orderable' => false, 'render' => 'return "<div class=\"btn-group btn-group-xs\">' . join('', $actions) . '</div>"'];

        array_push($this->tableColumns, $params);
    }

    public function queryGetAll()
    {
        $modelName = \Phalcon\Text::camelize(Inflector::singularize($this->router->getControllerName()));
        return $this->modelsManager->createBuilder()
            ->from($modelName);
    }

    public function indexAction()
    {
        if (method_exists($this, 'table')) {
            $this->table();
        }

        if ($this->request->isAjax() && $this->request->isPost()) {
            $builder = $this->queryGetAll();

            $columns = [];
            foreach ($this->tableColumns as $column) {
                if (isset($column['alias'])) {
                    array_push($columns, [$column[0], 'alias' => $column['alias']]);
                } else {
                    array_push($columns, $column[0]);
                }
            }

            $dataTables = new DataTable();
            return $dataTables->fromBuilder($builder, $columns)->sendResponse();
        }

        $this->view->partial($this->config->application->viewsDir . 'contents/table', ['title' => 'List Data', 'columns' => $this->tableColumns]);
    }
}
