<?php

namespace Gazlab\Admin\Controllers;

use Cake\Utility\Inflector;
use DataTables\DataTable;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;

class ResourceController extends ControllerBase
{
    private $tableColumns = [];
    private $formFields = [];
    private $modelName;

    public function initialize()
    {
        $this->modelName = \Phalcon\Text::camelize(Inflector::singularize($this->router->getControllerName()));

        parent::initialize();
    }

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
            array_push($actions, '<a href="' . $this->url->get(join('/', [$this->router->getControllerName(), 'update'])) . '/\'+row.DT_RowId+\'" class="btn btn-default" title="Edit"><i class="fa fa-edit"></i></a>');
        }
        // Delete Actions
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'delete')) {
            $buttons = [];
            array_push($buttons, $this->escaper->escapeHtmlAttr('<a href="' . $this->url->get(join('/', [$this->router->getControllerName(), 'delete'])) . '/') . '\'+row.DT_RowId+\'' . $this->escaper->escapeHtmlAttr('" class="btn btn-danger btn-yes-delete">Yes</a>'));
            array_push($buttons, $this->escaper->escapeHtmlAttr('<a role="button" class="btn btn-default">No</a>'));
            array_push($actions, '<a tabindex="0" class="btn btn-lg btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="Are you sure?" data-content="' . $this->escaper->escapeHtmlAttr('<div class="btn-group">') . join('', $buttons) . $this->escaper->escapeHtmlAttr('</div>') . '"><i class="fa fa-trash"></i></a>');
        }

        $params['header'] = '';
        $params['dataTable'] = ['data' => 'DT_Actions', 'searchable' => false, 'orderable' => false, 'render' => "return '<div class=\"btn-group btn-group-sm\">" . join('', $actions) . "</div>'", 'className' => 'text-right'];

        array_push($this->tableColumns, $params);
    }

    public function queryGetAll()
    {
        return $this->modelsManager->createBuilder()
            ->from($this->modelName);
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
                if (isset($column[0])) {
                    if (isset($column['alias'])) {
                        array_push($columns, [$column[0], 'alias' => $column['alias']]);
                    } else {
                        array_push($columns, $column[0]);
                    }
                }
            }

            $dataTables = new DataTable();
            return $dataTables->fromBuilder($builder, $columns)->sendResponse();
        }

        $this->view->partial($this->config->application->viewsDir . 'contents/table', ['title' => 'List Data', 'columns' => $this->tableColumns]);
    }

    public function params()
    {
        return $this->request->getPost();
    }

    public function createAction()
    {
        if ($this->request->isPost()) {
            $modelName = $this->modelName;
            $rowData = new $modelName;
            foreach ($this->params() as $key => $value) {
                $rowData->$key = $value;
            }
            if ($rowData->save()) {
                $this->flashSession->success('Data has been saved');
                if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'update')) {
                    return $this->response->redirect(join('/', [$this->router->getControllerName(), 'update', $rowData->id]));
                }
                return $this->response->redirect(join('/', [$this->router->getControllerName()]));
            }

            foreach ($rowData->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        $formFields = new Form();
        if (method_exists($this, 'form')) {
            $this->form();
        }
        foreach ($this->formFields as $field) {
            $formFields->add($field);
        }

        $this->view->partial($this->config->application->viewsDir . 'contents/form', ['title' => 'New', 'fields' => $this->formFields]);
    }

    public function queryGetOne()
    {
        $modelName = $this->modelName;
        return $modelName::findFirst($this->dispatcher->getParams()[0]);
    }

    public function textField($params)
    {
        $element = new Text($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setAttribute('class', 'form-control');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            } else {
                $params['attr']['class'] = $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }
        array_push($this->formFields, $element);
    }

    public function dateField($params)
    {
        $element = new Text($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setAttributes(['class' => 'form-control', 'autocomplete' => 'off']);
        $element->setUserOption('element', 'datepicker');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            } else {
                $params['attr']['class'] = $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }
        array_push($this->formFields, $element);
    }

    public function fileField($params)
    {
        $element = new File($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setAttribute('class', 'form-control');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            } else {
                $params['attr']['class'] = $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }
        array_push($this->formFields, $element);
    }

    public function passwordField($params)
    {
        $element = new Password($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setAttribute('class', 'form-control');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            } else {
                $params['attr']['class'] = $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }
        array_push($this->formFields, $element);
    }

    public function selectField($params)
    {
        $name = $params[0];
        unset($params[0]);
        $options = $params[1];
        unset($params[1]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        unset($params['label']);

        $element = new Select($name, $options, $params);
        $element->setLabel($label);
        $element->setAttribute('class', 'select2');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            } else {
                $params['attr']['class'] = $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }
        array_push($this->formFields, $element);
    }

    public function updateAction()
    {
        $rowData = $this->queryGetOne();

        if ($this->request->isPost()) {
            foreach ($this->params() as $key => $value) {
                $rowData->$key = $value;
            }
            if ($rowData->save()) {
                $this->flashSession->success('Data has been changed');

                return $this->response->redirect(join('/', [$this->router->getControllerName(), 'update', $rowData->id]));
            }

            foreach ($rowData->getMessages() as $message) {
                $this->flash->error($message);
            }
        }

        $formFields = new Form($rowData);
        if (method_exists($this, 'form')) {
            $this->form();
        }
        foreach ($this->formFields as $field) {
            $formFields->add($field);
        }

        $this->view->partial($this->config->application->viewsDir . 'contents/form', ['title' => 'Edit', 'fields' => $formFields]);
    }

    public function historyAction($id)
    {
        $model = new $this->modelName;

        $this->dispatcher->forward([
            'namespace' => 'Gazlab\Admin\Controllers',
            'controller' => 'log-activities',
            'action' => 'index',
            'params' => [$model->getSource(), $id]
        ]);
    }

    public function deleteAction($id)
    {
        $this->view->disable();

        return $this->response->setJsonContent($id)->send();
    }
}
