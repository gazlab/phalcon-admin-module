<?php

namespace Gazlab\Admin\Controllers;

class LogActivitiesController extends ResourceController
{
    public function queryGetAll()
    {
        return $this->modelsManager->createBuilder()
            ->columns([
                'l.*',
                'l.id',
                'l.created_at',
                'u.username',
                'l.event_type'
            ])
            ->addFrom('Gazlab\Admin\Models\LogActivity', 'l')
            ->join('Gazlab\Admin\Models\User', 'u.id = l.user_id', 'u')
            ->where("table_name = :table_name:", ['table_name' => $this->dispatcher->getParams()[0]])
            ->andWhere("row_id = :row_id:", ['row_id' => $this->dispatcher->getParams()[1]]);
    }

    public function table()
    {
        $this->column(['l.created_at', 'header' => 'At']);
        $this->column(['event_type', 'header' => 'Event Action']);
        $this->column(['username', 'header' => 'By']);
        
    }
}
