<?php
namespace Gazlab\Admin\Controllers;

use DataTables\DataTable;
use Phalcon\Forms\Form;

class ResourceController extends ControllerBase
{
    public function params()
    {
        return $this->request->getPost();
    }

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
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'update')) {
            $actions[] = '<a href="' . $this->url->get(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'update'])) . '/\'+row.DT_RowId+\'" title="Edit" class="btn btn-xs btn-default"><i class="fa fa-edit"></i></a>';
        }
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'delete')) {
            $contents[] = $this->escaper->escapeHtml('<a href="' . $this->url->get(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'delete']))) . '/\'+row.DT_RowId+\''.$this->escaper->escapeHtml('" class="btn btn-danger">Yes</a>');
            $contents[] = $this->escaper->escapeHtml('<a href="#" class="btn btn-default">No</a>');
            $actions[] = '<a tabindex="0" data-trigger="focus" title="Are you sure?" class="btn btn-xs btn-danger" data-toggle="popover" data-content="'.join(' ', $contents).'"><i class="fa fa-trash"></i></a>';
        }

        $params['render'] = 'function( data, type, row, meta ){return \'' . join(' ', $actions) . '\'}';
        $params['data'] = 'actions';
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

    public $formAttributes = [];
    public function setFormAttributes($attributes = [])
    {
        $this->formAttributes = $attributes;
    }

    // CREATE
    public $formFields = [];
    public function textField($params)
    {
        $element = new \Phalcon\Forms\Element\Text($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setAttributes(['class' => 'form-control']);
        array_push($this->formFields, $element);
    }
    public function textArea($params)
    {
        $element = new \Phalcon\Forms\Element\Textarea($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        unset($params['label']);
        $params['class'] = isset($params['class']) ? $params['class']. ' form-control' : 'form-control';
        $element->setLabel($label);
        $element->setAttributes($params);
        array_push($this->formFields, $element);
    }
    public function passwordField($params)
    {
        $element = new \Phalcon\Forms\Element\Password($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setAttributes(['class' => 'form-control']);
        array_push($this->formFields, $element);
    }
    public function fileField($params)
    {
        $element = new \Phalcon\Forms\Element\File($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        if (isset($params['showFiles'])){
            $element->setUserOption('showFiles', $params['showFiles']);
        }
        // $element->setAttributes(['class' => 'form-control']);
        array_push($this->formFields, $element);
    }
    public function select($params)
    {
        $element = new \Phalcon\Forms\Element\Select($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        
        $key = $params['using'][0];
        $value = $params['using'][1];
        $options = [];
        foreach ($params[1] as $option){
            $option = (array) $option;
            $options[$option[$key]] = $option[$value];
        }
        $element->setOptions($options);
        // $element->setAttributes(['class' => 'form-control']);
        array_push($this->formFields, $element);
    }
    public function selectStatic($params)
    {
        $element = new \Phalcon\Forms\Element\Select($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setOptions($params[1]);
        // $element->setAttributes(['class' => 'form-control']);
        array_push($this->formFields, $element);
    }
    public function createAction()
    {
        if ($this->request->isPost()) {
            $modelName = ucwords(\Phalcon\Text::camelize($this->router->getCOntrollerName()));
            $model = new $modelName();
            if (!$model->save($this->params())) {
                foreach ($model->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flashSession->success('Data has been saved');
                return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'update', $model->id]));
            }
        }

        $formFields = new Form();
        $this->form();

        foreach ($this->formFields as $field) {
            $formFields->add($field);
        }
        $this->view->partial($this->config->application->viewsDir . 'contents/form', ['title' => 'New', 'formFields' => $formFields, 'box' => true, 'attrs'=>$this->formAttributes]);
    }
    public function isCreateAction()
    {
        return $this->router->getActionName() === 'create';
    }

    // UPDATE
    public function isUpdateAction()
    {
        return $this->router->getActionName() === 'update';
    }
    public function queryGetOne()
    {
        $modelName = ucwords(\Phalcon\Text::camelize($this->router->getCOntrollerName()));
        return $modelName::findFirst($this->dispatcher->getParams()[0]);
    }
    public function updateAction()
    {
        $model = $this->queryGetOne();
        if ($this->request->isPost()) {
            if (!$model->save($this->params())) {
                foreach ($model->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flashSession->success('Data has been updated');
                return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'update', $model->id]));
            }
        }

        $formFields = new Form($model);
        $this->form();
        
        foreach ($this->formFields as $field) {
            $formFields->add($field);
        }
        $this->view->partial($this->config->application->viewsDir . 'contents/form', ['title' => 'Edit', 'formFields' => $formFields, 'box' => true, 'attrs'=>$this->formAttributes]);
    }

    // DELETE
    public function deleteAction()
    {
        $model = $this->queryGetOne();
        if (!$model->delete()){
            foreach ($model->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        }else {
            $this->flashSession->warning('Data has been deleted');
        }
        return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName()]));
    }
}
