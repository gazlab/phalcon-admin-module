<?php
namespace Gazlab\Admin\Controllers;

use \DataTables\DataTable;

class ResourceController extends ControllerBase
{
    private $columns = [];
    private $formRows = [];

    public function queryMethod()
    {
        return 'ResultSet';
    }

    public function indexAction()
    {
        if ($this->request->isAjax()) {
            $method = $this->queryMethod();
            $f = 'from' . $method;
            $dataTables = new DataTable();
            $dataTables->$f($this->query())->sendResponse();
        }

        $this->table();

        $this->view->setVars(
            [
                'contents' => [
                    ['table', 'columns' => $this->columns, 'title' => 'List', 'card' => true],
                ],
            ]
        );
        $this->view->pick(__DIR__ . '/../views/templates/content');

        $this->assets->addCss('gazlab_assets/plugins/datatables/dataTables.bootstrap4.css');
        $this->assets->addJs('gazlab_assets/plugins/datatables/jquery.dataTables.js');
        $this->assets->addJs('gazlab_assets/plugins/datatables/dataTables.bootstrap4.js');
    }

    public function column($params)
    {
        $params['header'] = isset($params['header']) ? $params['header'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->columns, $params);
    }

    public function actions()
    {
        $params[0] = 'actions';
        $params['header'] = '';
        $params['searchable'] = false;
        $params['orderable'] = false;
        $params['render'] = 'function(){
            return "actions";
        }';
        return array_push($this->columns, $params);
    }

    public function createAction()
    {
        $this->form();

        $this->view->setVars(
            [
                'contents' => [
                    ['form', 'formRows' => $this->formRows, 'title' => 'New', 'card' => true],
                ],
            ]
        );
        $this->view->pick(__DIR__ . '/../views/templates/content');
    }
}
