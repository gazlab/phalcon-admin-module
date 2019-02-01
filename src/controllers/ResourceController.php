<?php
namespace Gazlab\Admin\Controllers;

use \DataTables\DataTable;

class ResourceController extends ControllerBase
{
    private $columns = [];
    private $formRows = [];

    private function queryMethod($method = 'ResultSet')
    {
        return $method;
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

    public function textField($params)
    {
        $params['tag'] = 'textField';
        $params['class'] = isset($params['class']) ? $params['class'] . ' form-control' : 'form-control';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }

    public function fileField($params)
    {
        $params['tag'] = 'fileField';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }

    public function textArea($params)
    {
        $params['tag'] = 'textArea';
        $params['class'] = isset($params['class']) ? $params['class'] . ' form-control' : 'form-control';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }
}
