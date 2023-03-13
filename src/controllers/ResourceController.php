<?php

namespace Gazlab\Admin\Controllers;

use DataTables\DataTable;
use Phalcon\Forms\Form;

class ResourceController extends ControllerBase
{
    public function table()
    {
    }

    public function form()
    {
    }

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
        $actions = [];

        if (count($extras)) {
            foreach ($extras as $extra) {
                if (isset($extra['popover'])) {
                    $extra['name'] = isset($extra['name']) ? $extra['name'] : ucwords(\Phalcon\Text::humanize(\Phalcon\Text::uncamelize($extra[0], '-')));
                    foreach ($extra['popover'] as $btnAction) {
                        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), $btnAction[0])) {
                            $btnAction['name'] = isset($btnAction['name']) ? $btnAction['name'] : ucwords(\Phalcon\Text::humanize(\Phalcon\Text::uncamelize($btnAction[0], '-')));
                            $elementAttr = [];
                            if (isset($btnAction['attr'])) {
                                foreach ($btnAction['attr'] as $key => $value) {
                                    $elementAttr[] = $key . '="' . $value . '"';
                                }
                            }

                            $btnActions[] = $this->escaper->escapeHtml('<a href="' . $this->url->get(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), $btnAction[0]]))) . '/\'+row.DT_RowId+\'' . $this->escaper->escapeHtml('" title="' . $btnAction['name'] . '" class="btn btn-default" ' .  join(' ', $elementAttr) .  '><i class="' . $btnAction['icon'] . '"></i> ' . $btnAction['name'] . '</a>');
                        }
                    }
                    $actions[] = '<a tabindex="0" data-trigger="focus" title="' . $extra['name'] . '" class="btn btn-default" data-toggle="popover" data-content="' . $this->escaper->escapeHtml('<div class="btn-group-vertical">') . join('', $btnActions) . $this->escaper->escapeHtml('</div>') . '"><i class="' . $extra['icon'] . '"></i></a>';
                } else {
                    if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), $extra[0])) {
                        $extra['name'] = isset($extra['name']) ? $extra['name'] : ucwords(\Phalcon\Text::humanize(\Phalcon\Text::uncamelize($extra[0], '-')));
                        $elementAttr = [];
                        if (isset($extra['attr'])) {
                            foreach ($extra['attr'] as $key => $value) {
                                $elementAttr[] = $key . '="' . $value . '"';
                            }
                        }

                        $actions[] = '<a href="' . $this->url->get(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), $extra[0]])) . '/\'+row.DT_RowId+\'" title="' . $extra['name'] . '" class="btn btn-default" ' . join(' ', $elementAttr) . '><i class="' . $extra['icon'] . '"></i></a>';
                    }
                }
            }
        }

        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'update')) {
            $actions[] = '<a href="' . $this->url->get(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'update'])) . '/\'+row.DT_RowId+\'" title="Edit" class="btn btn-default"><i class="fa fa-edit"></i></a>';
        }
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'delete')) {
            $contents[] = $this->escaper->escapeHtml('<a href="' . $this->url->get(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'delete']))) . '/\'+row.DT_RowId+\'' . $this->escaper->escapeHtml('" class="btn btn-danger">Yes</a>');
            $contents[] = $this->escaper->escapeHtml('<a href="#" class="btn btn-default">No</a>');
            $actions[] = '<a tabindex="0" data-trigger="focus" title="Are you sure?" class="btn btn-danger" data-toggle="popover" data-content="' . $this->escaper->escapeHtml('<div class="btn-group" role="group" aria-label="Delete">') . join('', $contents) . $this->escaper->escapeHtml('</div>') . '"><i class="fa fa-trash"></i></a>';
        }

        $params['render'] = 'function( data, type, row, meta ){return \'<div class="btn-group btn-group-sm btn-block" role="group" aria-label="Actions">' . join('', $actions) . '</div>\'}';
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
        $this->view->partial($this->config->application->viewsDir . 'contents/table', ['columns' => $this->columns, 'box' => true, 'options' => $this->tableOptions]);
    }

    public $formAttributes = [];

    public function setFormAttributes($attributes = [])
    {
        $this->formAttributes = $attributes;
    }

    public $tableOptions = [];

    public function setTableOptions($options = [])
    {
        $this->tableOptions = $options;
    }

    // CREATE
    public $formFields = [];
    public $insertId = null;

    public function textField($params)
    {
        $element = new \Phalcon\Forms\Element\Text($params[0]);
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
    // public function dateField($params)
    // {
    //     $element = new \Phalcon\Forms\Element\Text($params[0]);
    //     $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
    //     $element->setLabel($label);
    //     $element->setAttributes(['class' => 'form-control date-picker']);
    //     array_push($this->formFields, $element);
    // }
    public function textArea($params)
    {
        $element = new \Phalcon\Forms\Element\Textarea($params[0]);
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

        if (isset($params['help_message'])) {
            $element->setUserOption('help_message', $params['help_message']);
        }

        array_push($this->formFields, $element);
    }

    public function passwordField($params)
    {
        $element = new \Phalcon\Forms\Element\Password($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);

        $element->setAttribute('class', 'form-control');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }

        array_push($this->formFields, $element);
    }

    public function fileField($params)
    {
        $element = new \Phalcon\Forms\Element\File($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        if (isset($params['showFiles'])) {
            $element->setUserOption('showFiles', $params['showFiles']);
        }
        if (isset($params['help_message'])) {
            $element->setUserOption('help_message', $params['help_message']);
        }
        if (isset($params['attr'])) {
            $element->setAttributes($params['attr']);
        }
        // $element->setAttributes(['class' => 'form-control']);
        array_push($this->formFields, $element);
    }

    public function select($params)
    {
        $element = new \Phalcon\Forms\Element\Select($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);

        if (isset($params['useEmpty']) && $params['useEmpty'] === true) {
            $emptyValue = isset($params['emptyValue']) ? $params['emptyValue'] : '';
            $emptyText = isset($params['emptyText']) ? $params['emptyText'] : 'Choose...';
            $element->addOption([$emptyValue => $emptyText]);
        }

        $key = $params['using'][0];
        $value = $params['using'][1];
        $options = [];
        foreach ($params[1] as $option) {
            $option = (array) $option;
            $element->addOption([$option[$key] => $option[$value]]);
        }

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

    public function selectStatic($params)
    {
        $element = new \Phalcon\Forms\Element\Select($params[0]);
        $label = isset($params['label']) ? $params['label'] : ucwords(\Phalcon\Text::humanize($params[0]));
        $element->setLabel($label);
        $element->setOptions($params[1]);
        $element->setAttribute('class', 'select2');
        if (isset($params['attr'])) {
            if (isset($params['attr']['class'])) {
                $params['attr']['class'] .= ' ' . $element->getAttribute('class');
            }
            $element->setAttributes($params['attr']);
        }
        array_push($this->formFields, $element);
    }

    public function setInsertId($id)
    {
        $this->insertId = $id;
    }

    public function getInsertId()
    {
        return $this->insertId;
    }

    public $modelName = null;

    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    public function getModelName()
    {
        return $this->modelName !== null ? $this->modelName : ucwords(\Phalcon\Text::camelize($this->router->getCOntrollerName()));
    }

    public function createAction()
    {
        if ($this->request->isPost()) {
            if (method_exists($this, 'beforeCreate')) {
                $this->beforeCreate();
            }
            if (method_exists($this, 'beforeSave')) {
                $this->beforeSave();
            }

            $modelName = $this->getModelName();
            $this->logger->debug($modelName);
            $model = new $modelName();
            foreach ($this->params() as $field => $value) {
                $model->$field = $value;
            }
            if (!$model->save()) {
                foreach ($model->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                if (method_exists($this, 'afterCreate')) {
                    $this->afterCreate();
                }
                if (method_exists($this, 'afterSave')) {
                    $this->setInsertId($model->id);
                    $this->afterSave();
                }

                $this->flashSession->success('Data has been saved');

                if ($this->acl->isAllowed($this->userSession->profile->name, $this->router->getControllerName(), 'update')) {
                    return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'update', $model->id]));
                } else {
                    return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName()]));
                }
            }
        }

        $formFields = new Form();
        $this->form();

        if (count($this->formFields) > 0) {
            foreach ($this->formFields as $field) {
                $formFields->add($field);
            }
            $this->view->partial($this->config->application->viewsDir . 'contents/form', ['title' => 'New', 'formFields' => $formFields, 'box' => true, 'attrs' => $this->formAttributes]);
        }
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
        $modelName = $this->getModelName();
        return $modelName::findFirst($this->dispatcher->getParams()[0]);
    }

    public function updateAction()
    {
        $model = $this->queryGetOne();
        if ($this->request->isPost()) {
            if (method_exists($this, 'beforeUpdate')) {
                $this->beforeUpdate();
            }
            if (method_exists($this, 'beforeSave')) {
                $this->beforeSave();
            }

            foreach ($this->params() as $field => $value) {
                $model->$field = $value;
            }
            if (!$model->save()) {
                foreach ($model->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                if (method_exists($this, 'afterUpdate')) {
                    $this->afterUpdate();
                }
                if (method_exists($this, 'afterSave')) {
                    $this->afterSave();
                }

                $this->flashSession->success('Data has been updated');
                return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName(), 'update', $model->id]));
            }
        }

        $formFields = new Form($model);
        $this->form();

        foreach ($this->formFields as $field) {
            $formFields->add($field);
        }
        $this->view->partial($this->config->application->viewsDir . 'contents/form', ['title' => 'Edit', 'formFields' => $formFields, 'box' => true, 'attrs' => $this->formAttributes]);
    }

    // DELETE
    public function deleteAction()
    {
        $model = $this->queryGetOne();
        if (!$model->delete()) {
            foreach ($model->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        } else {
            $this->flashSession->warning('Data has been deleted');
        }
        return $this->response->redirect(join('/', [$this->router->getModuleName(), $this->router->getControllerName()]));
    }
}
