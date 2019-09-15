<?php

use Cake\Utility\Inflector;
use Doctrine\Common\Inflector\Inflector as DoctrineInflector;

class ResourceTask extends TaskBase
{
    public function createAction(array $params)
    {
        if (!isset($params['name'])) {
            echo 'Please give the name of resource (--name=Foo).';
            return false;
        }
        if (!isset($params['moduleName'])) {
            echo 'Please give the module name (--moduleName=admin).';
            return false;
        }

        $singular = Inflector::singularize($params['name']);
        $plural = Inflector::pluralize($singular);

        $modelsDir = 'app/common/models/';
        if (isset($params['modelsDir'])) {
            $modelsDir = $params['modelsDir'];
        }
        $modelName = Inflector::classify($singular);
        $modelFile = $modelsDir . $modelName . '.php';
        if (!file_exists($modelFile)) {
            $templateContent = file_get_contents(__DIR__ . '/../templates/model');
            $templateContent = str_replace('$ClassName', $modelName, $templateContent);
            $templateContent = str_replace('$TableName', Inflector::tableize($plural), $templateContent);
            file_put_contents($modelFile, $templateContent);
            echo 'created ' . $modelFile . PHP_EOL;
        }

        $controllersDir = 'app/modules/' . $params['moduleName'] . '/controllers/';
        if (isset($params['controllersDir'])) {
            $controllersDir = $params['controllersDir'];
        }
        $controllerName = DoctrineInflector::classify($plural) . 'Controller';
        $controllerFile = $controllersDir . $controllerName . '.php';
        if (!file_exists($controllerFile)) {
            $templateContent = file_get_contents(__DIR__ . '/../templates/controller');
            $templateContent = str_replace('$ClassName', $controllerName, $templateContent);
            $templateContent = str_replace('$ResourceName', $this::slugify($plural), $templateContent);
            file_put_contents($controllerFile, $templateContent);
            echo 'created ' . $controllerFile . PHP_EOL;
        }

        // if (!isset($params['columns'])) {
        //     echo 'Please give the column of table resource.';
        //     return false;
        // }

        // $columns = explode(',', $params['columns']);
        // $columns = array_map('trim', $columns);
        // foreach ($columns as $col) {
        //     list($column, $dataType) = explode(':', $col);
        // }
    }

    public static function slugify(string $text): string
    {
        return str_replace('_', '-', Inflector::tableize($text));
    }
}
