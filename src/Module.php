<?php

namespace Gazlab\Admin;

use Phalcon\Avatar\Gravatar;
use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;

class Module implements ModuleDefinitionInterface
{
    /**
     * Registers an autoloader related to the module
     *
     * @param DiInterface $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces([
            'Gazlab\Admin\Controllers' => __DIR__ . '/controllers/',
            'Gazlab\Admin\Models' => __DIR__ . '/models/',
        ]);

        $loader->registerDirs([
            APP_PATH . '/modules/' . $di->get('dispatcher')->getModuleName() . '/controllers',
        ]);

        $loader->register();
    }

    /**
     * Registers services related to the module
     *
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        /**
         * Try to load local configuration
         */
        if (file_exists(__DIR__ . '/config/config.php')) {

            $config = $di['config'];

            $override = new Config(include __DIR__ . '/config/config.php');

            if ($config instanceof Config) {
                $config->merge($override);
            } else {
                $config = $override;
            }
        }

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            $config = $this->getConfig();

            $view = new View();
            $view->setViewsDir(APP_PATH . '/modules/' . $this->get('dispatcher')->getModuleName() . '/views');

            $view->registerEngines([
                '.volt' => 'voltShared',
                '.phtml' => PhpEngine::class,
            ]);

            $view->setMainView(__DIR__ . '/views/index');
            $view->setLayoutsDir(__DIR__ . '/views/layouts/');

            return $view;
        };

        /**
         * Database connection is created based in the parameters defined in the configuration file
         */
        $di['db'] = function () {
            $config = $this->getConfig();

            $dbConfig = $config->database->toArray();

            $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $dbConfig['adapter'];
            unset($config['adapter']);

            return new $dbAdapter($dbConfig);
        };

        $di->setShared('gravatar', function () {
            // Get Gravatar instance
            $gravatar = new Gravatar([]);

            $gravatar->setDefaultImage('wavatar')
                ->setSize(160)
                ->setRating(Gravatar::RATING_PG);

            return $gravatar;
        });
    }
}
