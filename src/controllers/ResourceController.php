<?php

namespace Gazlab\Admin\Controllers;

use Phalcon\Text;
use \DataTables\DataTable;
use Phalcon\Forms\Form;

class ResourceController extends ControllerBase
{
    public $columns = [];
    public $fields = [];

    public function column($params)
    {
        $params['data'] = isset($params['alias']) ? $params['alias'] : $params[0];
        $params['title'] = isset($params['header']) ? $params['header'] : ucwords(Text::humanize($params['data']));

        array_push($this->columns, $params);
    }

    public function actions($actions = [])
    {
        $render = [];
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->dispatcher->getControllerName(), 'update')) {
            array_push($render, '<a href="' . $this->url->get('/' . join('/', [$this->dispatcher->getControllerName(), 'update'])) . '/\'+row.DT_RowId+\'" class="btn btn-default" title="Edit"><i class="fas fa-edit fa-sm   "></i></a>');
        }
        if ($this->acl->isAllowed($this->userSession->profile->name, $this->dispatcher->getControllerName(), 'delete')) {
            $buttons = [];
            array_push($buttons, $this->escaper->escapeHtmlAttr('<a href="' . $this->url->get('/' . join('/', [$this->dispatcher->getControllerName(), 'delete'])) . '/') . '\'+row.DT_RowId+\'' . $this->escaper->escapeHtmlAttr('" class="btn btn-danger btn-yes-delete">Yes</a>'));
            array_push($buttons, $this->escaper->escapeHtmlAttr('<a role="button" class="btn btn-default">No</a>'));
            array_push($render, '<a tabindex="0" class="btn btn-lg btn-danger" role="button" data-toggle="popover" data-trigger="focus" title="Are you sure?" data-content="' . $this->escaper->escapeHtmlAttr('<div class="btn-group">') . join('', $buttons) . $this->escaper->escapeHtmlAttr('</div>') . '"><i class="fas fa-trash fa-sm  "></i></a>');
        }

        array_push($this->columns, ['searchable' => false, 'orderable' => false, 'render' => "return '<div class=\"btn-group btn-group-sm\" role=\"group\">" . join('', $render) . "</div>'"]);
    }

    public function queryGetAll()
    {
        return $this->modelsManager->createBuilder()
            ->from(\Phalcon\Text::camelize($this->dispatcher->getControllerName()));
    }

    public function indexAction()
    {
        if (!method_exists($this, 'table')) {
        } else {
            $this->table();
        }

        if ($this->request->isAjax()) {
            $query = $this->queryGetAll();

            $columns = [];
            foreach ($this->columns as $column) {
                if (isset($column[0])) {
                    if (isset($column['alias'])) {
                        array_push($columns, [$column[0], 'alias' => $column['alias']]);
                    } else {
                        array_push($columns, $column[0]);
                    }
                }
            }

            $dataTables = new DataTable();
            $dataTables->fromBuilder($query, $columns)->sendResponse();
        }

        $this->view->setVars([
            'columns' => $this->columns
        ]);
        $this->view->pick('contents/table');
    }

    public function params()
    {
        return $this->request->getPost();
    }

    public function createAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $modelName = \Phalcon\Text::camelize($this->dispatcher->getControllerName());
                $model = new $modelName;

                if (!$model->save($this->params())) {
                    foreach ($model->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->flashSession->success('Data has been saved');
                    if ($this->acl->isAllowed($this->userSession->profile->name, $this->dispatcher->getControllerName(), 'update')) {
                        return $this->response->redirect('/' . join('/', [$this->dispatcher->getControllerName(), 'update', $model->id]));
                    } else {
                        return $this->response->redirect('/' . join('/', [$this->dispatcher->getControllerName()]));
                    }
                }
            }
        }

        $this->fields = new Form();
        if (!method_exists($this, 'form')) {
        } else {
            $this->form();
        }
        $this->view->setVars([
            'title' => 'New ' . $this->resource->menu['name'],
            'fields' => $this->fields
        ]);
        $this->view->pick('contents/form');
    }

    public function queryGetOne()
    {
        $modelName = \Phalcon\Text::camelize($this->dispatcher->getControllerName());
        return $modelName::findFirst($this->dispatcher->getParams()[0]);
    }

    public function updateAction()
    {
        $model = $this->queryGetOne();
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                if (!$model->save($this->params())) {
                    foreach ($model->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->flashSession->success('Data has been updated');

                    return $this->response->redirect('/' . join('/', [$this->dispatcher->getControllerName(), 'update', $model->id]));
                }
            }
        }

        $this->fields = new Form($model);
        if (!method_exists($this, 'form')) {
        } else {
            $this->form();
        }
        $this->view->setVars([
            'title' => 'Editing ' . $this->resource->menu['name'],
            'fields' => $this->fields
        ]);
        $this->view->pick('contents/form');
    }

    private function addField($elementName, $params)
    {
        $params['name'] = isset($params['name']) ? $params['name'] : $params[0];
        unset($params[0]);
        $className = '\Phalcon\Forms\Element\\' . Text::camelize($elementName);
        $element = new $className($params['name']);
        $params['label'] = isset($params['label']) ? $params['label'] : ucwords(Text::humanize($params['name']));
        $element->setLabel($params['label']);
        unset($params['name']);
        unset($params['label']);
        $params['class'] = isset($params['class']) ? $params['class'] : null;
        switch ($elementName) {
            case 'select':
                $element->setOptions($params[1]);
                unset($params[1]);

                $params['class'] = $params['class'] . ' select2';
                break;
            default:
                $params['class'] = $params['class'] . ' form-control';
        }

        $element->setAttributes($params);

        $this->fields->add(
            $element
        );
    }

    public function textField($params)
    {
        $this->addField('text', $params);
    }

    public function textArea($params)
    {
        $this->addField('text_area', $params);
    }

    public function select($params)
    {
        $this->addField('select', $params);
    }

    public function deleteAction()
    {
        $this->view->disable();
        if ($this->request->isDelete()) {
            $rowData = $this->queryGetOne();
            $this->flash->setImplicitFlush(false);
            if (!$rowData->delete()) {
                $messages = [];
                foreach ($rowData->getMessages() as $msg) {
                    array_push($messages, $this->flash->error($msg));
                }
                $messages = join('', $messages);
            } else {
                $messages = $this->flash->warning('Data has been deleted');
            }
            return $this->response->setContent($messages)->send();
        }
    }

    public function isCreateAction()
    {
        return $this->dispatcher->getActionName() === 'create';
    }
    
    public function isUpdateAction()
    {
        return $this->dispatcher->getActionName() === 'update';
    }
    
    public function isDeleteAction()
    {
        return $this->dispatcher->getActionName() === 'delete';
    }
}
