<?php

class InstallTask extends TaskBase
{
    public function mainAction(array $params)
    {
        // Module Name
        $moduleName = 'admin';
        if (isset($params['name'])){
            $moduleName = $params['name'];
        }

        echo $moduleName;
    }
}
