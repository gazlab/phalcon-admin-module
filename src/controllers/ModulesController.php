<?php
namespace Gazlab\Admin\Controllers;

class ModulesController extends ResourceController
{
    public function table()
    {
        $this->column(['id']);
        $this->column(['name']);
        $this->column(['group']);

        $this->actions();
    }
}
