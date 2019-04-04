<?php
namespace Gazlab\Admin\Controllers;

use \DataTables\DataTable;

class ResourceController extends ControllerBase
{
    private $columns = [];
    private $formRows = [];
    private $queryMethod = 'ResultSet';

    public function getResourceName()
    {
        return str_replace('Controller', '', get_class($this));
    }

    public function setQueryMethod($method)
    {
        $this->queryMethod = $method;
    }

    public function queryGetAll()
    {
        $modelName = $this->getResourceName();

        return $modelName::find();
    }

    public function queryGetOne()
    {
        $modelName = $this->getResourceName();

        return $modelName::findFirst($this->dispatcher->getParams()[0]);
    }

    public function indexAction()
    {
        $this->table();

        if ($this->request->isAjax()) {
            $method = $this->queryMethod;
            $f = 'from' . $method;
            $dataTables = new DataTable();
            $dataTables->$f($this->queryGetAll())->sendResponse();
        }

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

        // Update Action
        $actions[] = '<a href="' . $this->url->get($this->router->getModuleName() . '/' . $this->router->getControllerName() . '/update') . '/\'+row.DT_RowId+\'" title="Edit" class="btn btn-sm btn-light"><i class="fa fa-edit"></i></a>';

        // Delete Action
        $content = $this->escaper->escapeHtml('<a href="'.$this->url->get(join('/', [
            $this->router->getModuleName(),
            $this->router->getControllerName(),
            'delete',
        ])).'/') . '\'+row.DT_RowId+\'' . $this->escaper->escapeHtml('" class="btn btn-danger">Yes</a> <a role="button" class="btn btn-default">No</a>');
        $actions[] = '<a tabindex="0" role="button" title="Are you sure?" class="btn btn-sm btn-danger" data-toggle="popover" data-content="' . $content . '"><i class="fa fa-trash"></i></a>';

        $params['render'] = 'function(data, type, row, meta){
            return \'' . join(' ', $actions) . '\';
        }';

        return array_push($this->columns, $params);
    }

    public function createAction()
    {
        if ($this->request->isPost()) {
            $modelName = $this->getResourceName();

            $table = new $modelName();
            foreach ($this->params() as $field => $value){
                $table->$field = $value;
            }
            if (!$table->save()) {
                foreach ($table->getMessages() as $error) {
                    $this->flash->error($error);
                }
            } else {
                $this->flashSession->success('Data has been saved.');
                return $this->response->redirect($this->router->getModuleName() . '/' . $this->router->getControllerName() . '/update/' . $table->id);
            }
        }

        $this->form();

        $this->view->setVars(
            [
                'contents' => [
                    ['form', 'formRows' => $this->formRows, 'title' => 'New', 'card' => true, 'params' => ['enctype' => 'multipart/form-data']],
                ],
            ]
        );
        $this->view->pick(__DIR__ . '/../views/templates/content');
    }

    public function updateAction()
    {
        $table = $this->queryGetOne();
        if ($this->request->isPost()) {
            foreach ($this->params() as $field => $value){
                $table->$field = $value;
            }
            if (!$table->save()) {
                foreach ($table->getMessages() as $error) {
                    $this->flash->error($error);
                }
            } else {
                $this->flashSession->success('Data has been updated.');
                return $this->response->redirect($this->router->getModuleName() . '/' . $this->router->getControllerName() . '/update/' . $table->id);
            }
        }
        
        $this->tag->setDefaults($table->toArray());

        $this->form();

        $this->view->setVars(
            [
                'contents' => [
                    ['form', 'formRows' => $this->formRows, 'title' => 'Edit', 'card' => true, 'params' => ['enctype' => 'multipart/form-data']],
                ],
            ]
        );
        $this->view->pick(__DIR__ . '/../views/templates/content');
    }

    public function deleteAction()
    {
        $this->view->disable();

        $table = $this->queryGetOne();
        
            if (!$table->delete()) {
                foreach ($table->getMessages() as $error) {
                    $this->flash->error($error);
                }
            } else {
                $this->flashSession->warning('Data has been deleted.');
                return $this->response->redirect($this->router->getModuleName() . '/' . $this->router->getControllerName());
            }
    }

    public function params()
    {
        return $this->request->getPost();
    }

    public function textField($params)
    {
        $params['tag'] = 'textField';
        $params['class'] = isset($params['class']) ? $params['class'] . ' form-control' : 'form-control';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }

    public function passwordField($params)
    {
        $params['tag'] = 'passwordField';
        $params['class'] = isset($params['class']) ? $params['class'] . ' form-control' : 'form-control';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }

    public function numericField($params)
    {
        $params['tag'] = 'numericField';
        $params['class'] = isset($params['class']) ? $params['class'] . ' form-control' : 'form-control';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }

    public function fileField($params)
    {
        $params['tag'] = 'fileField';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        $params['value'] = null;
        return array_push($this->formRows, $params);
    }

    public function select($params)
    {
        $params['tag'] = 'select';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        $params['class'] = isset($params['class']) ? $params['class'] . ' select2' : 'select2';
        return array_push($this->formRows, $params);
    }

    public function selectStatic($params)
    {
        $params['tag'] = 'selectStatic';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        $params['class'] = isset($params['class']) ? $params['class'] . ' select2' : 'select2';
        return array_push($this->formRows, $params);
    }

    public function textArea($params)
    {
        $params['tag'] = 'textArea';
        $params['class'] = isset($params['class']) ? $params['class'] . ' form-control' : 'form-control';
        $params['label'] = isset($params['label']) ? $params['label'] : \Phalcon\Text::humanize($params[0]);
        return array_push($this->formRows, $params);
    }

    public function isCreateAction()
    {
        return $this->router->getActionName() === 'create';
    }

    public function isUpdateAction()
    {
        return $this->router->getActionName() === 'update';
    }
}
