<?php

class InstallTask extends TaskBase
{
    public function mainAction(array $params)
    {
        // Module Name
        $moduleName = 'admin';
        if (isset($params['name'])) {
            $moduleName = $params['name'];
        }

        // Modules Dir
        $modulesDir = 'app/modules/';
        if (isset($params['modulesDir'])) {
            $modulesDir = $params['modulesDir'];
        }

        // Module Dir
        $moduleDir = $modulesDir . $moduleName;
        if (!is_dir($moduleDir)) {
            $this->cmd('mkdir ' . $moduleDir);
        }
        // Config Dir
        $configDir = $moduleDir . '/config';
        if (!is_dir($configDir)) {
            $this->cmd('mkdir ' . $configDir);
        }
        $configGazlab = $configDir . '/gazlab.php';
        if (!file_exists($configGazlab)) {
            $templateContent = file_get_contents(__DIR__ . '/../templates/config_gazlab');
            $templateContent = str_replace('$BaseUri', $moduleName, $templateContent);
            file_put_contents($configGazlab, $templateContent);
            echo 'created ' . $configGazlab . PHP_EOL;
        }

        // Controllers Dir
        $controllersDir = $moduleDir . '/controllers';
        if (!is_dir($controllersDir)) {
            $this->cmd('mkdir ' . $controllersDir);
        }

        // Models Dir
        $modelsDir = $moduleDir . '/models';
        if (!is_dir($modelsDir)) {
            $this->cmd('mkdir ' . $modelsDir);
        }

        // Assets Dir
        $assetsDir = 'public/assets';
        if (!is_dir($assetsDir)) {
            $this->cmd('mkdir ' . $assetsDir);
        }
        // Link AdminLTE
        $adminlteLink = 'public/assets/adminlte';
        if (!file_exists($adminlteLink)) {
            $this->cmd('ln -s "../../vendor/almasaeed2010/adminlte" ' . $adminlteLink);
        }

        // Migrate
        $this->cmd('vendor/bin/phinx migrate -c vendor/gazlab/phalcon-admin-module/phinx.php');
        // Seed
        $this->cmd('vendor/bin/phinx seed:run -c vendor/gazlab/phalcon-admin-module/phinx.php');
    }

    private function cmd($cmd)
    {
        while (@ob_end_flush());
        $proc = popen($cmd, 'r');
        while (!feof($proc)) {
            echo fread($proc, 4096);
            @flush();
        }
    }
}
