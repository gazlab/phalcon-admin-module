<?php
namespace Gazlab\Admin\Controllers;

use Imove\Models\PortalNews;

class NewsController extends ResourceController
{
    public function query()
    {
        return PortalNews::find();
    }

    public function table()
    {
        $this->column(['title']);

        $this->actions();
    }

    public function form()
    {
        # code...
    }
}
