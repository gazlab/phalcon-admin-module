<?php

namespace Gazlab\Admin\Controllers;

use Phalcon\Text;

class ResourceController extends ControllerBase
{
    public $columns = [];

    public function column($params)
    {
        $params['data'] = $params[0];
        $params['title'] = isset($params['header']) ? $params['header'] : ucwords(Text::humanize($params['data']));

        array_push($this->columns, $params);
    }

    public function indexAction()
    {
        $this->view->pick('contents/table');
    }
}
