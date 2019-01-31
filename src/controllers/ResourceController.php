<?php
namespace Gazlab\Admin\Controllers;

class ResourceController extends ControllerBase
{
    private $columns = [];

    public function indexAction()
    {
        $this->table();

        $this->view->setVars(
            [
                'contents' => [
                    ['card', 'title' => 'List', 'contents' => [
                        ['table', 'columns' => $this->columns],
                    ]],
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
        $params['header'] = null;
        return array_push($this->columns, $params);
    }
}
