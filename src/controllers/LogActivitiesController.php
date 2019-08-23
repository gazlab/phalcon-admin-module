<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\LogActivity;

class LogActivitiesController extends ControllerBase
{
    public function indexAction($tableName, $rowId)
    {
        $logActivities = LogActivity::find([
            "table_name = ?0 AND row_id = ?1",
            'bind' => [
                $tableName,
                $rowId
            ]
        ])->toArray();
        print_r($logActivities);
    }
}
