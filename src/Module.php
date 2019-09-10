<?php

namespace Gazlab\Admin;

use Acl\Acl;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config;
use Phalcon\Avatar\Gravatar;
use Phalcon\Breadcrumbs;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Dispatcher;

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
            'Gazlab\Admin\Models'      => __DIR__ . '/models/',
            'Gazlab'      => __DIR__ . '/libraries/Gazlab/',
            'Acl'      => __DIR__ . '/libraries/Acl/'
        ]);

        $loader->registerDirs([
            APP_PATH . '/modules/' . $di['router']->getModuleName() . '/controllers/'
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

            $override = new Config(include APP_PATH . '/modules/' . $di['router']->getModuleName() . '/config/gazlab.php');

            if ($config instanceof Config) {
                $config->merge($override);
            } else {
                $config = $override;
            }
        }

        /**
         * Setting up the view component
         */
        $di['view'] = function () use ($di) {
            $config = $this->getConfig();

            $eventsManager = new Manager();
            $eventsManager->attach(
                'view:afterRender',
                function (Event $event, $view) {
                    $sourceHtml = $view->getContent();
                    $parser = \WyriHaximus\HtmlCompress\Factory::construct();
                    $compressedHtml = $parser->compress($sourceHtml);
                    $view->setContent(
                        (string) $compressedHtml
                    );
                }
            );

            $view = new View();
            $view->setViewsDir(APP_PATH . '/modules/' . $di['router']->getModuleName() . '/views/');
            $view->setMainView($config->application->viewsDir . 'index');
            $view->setLayoutsDir($config->application->viewsDir . 'layouts/');

            $view->registerEngines([
                '.volt'  => 'voltShared',
                '.phtml' => PhpEngine::class
            ]);

            $view->setEventsManager($eventsManager);

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

        $di['gravatar'] = function () {
            // Get Gravatar instance
            $gravatar = new Gravatar(
                []
            );

            // Setting default image, maximum size and maximum allowed Gravatar rating
            $gravatar->setDefaultImage('retro')
                ->setSize(160)
                ->enableSecureURL();

            return $gravatar;
        };

        $di['flashSession'] = function () {
            return new FlashSession([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        };

        $di['AclResources'] = function () {
            $pr = [];
            if (is_readable(__DIR__ . '/config/privateResources.php')) {
                $pr = include __DIR__ . '/config/privateResources.php';
                $pr = $pr->privateResources->toArray();
            }

            $sql = 'SELECT resource, action FROM ga_permissions GROUP BY resource, action';
            $permissions = $this->getShared('db')->fetchAll($sql);
            if (count($permissions) > 0) {
                foreach ($permissions as $permission) {
                    $pr[$permission['resource']][] = $permission['action'];
                }
            }

            return $pr;
        };

        $di['acl'] = function () {
            $acl = new Acl();
            $pr = $this->getShared('AclResources');
            $acl->addPrivateResources($pr);
            return $acl;
        };

        $di['breadcrumbs'] = function () {
            $breadcrumbs = new Breadcrumbs;
            $breadcrumbs->setSeparator('');
            $breadcrumbs->setLastNotLinked(true);

            return $breadcrumbs;
        };

        $di['dispatcher'] = function () {
            $pr = include __DIR__ . '/config/privateResources.php';
            $eventsManager = new Manager();
            $eventsManager->attach(
                'dispatch:beforeDispatchLoop',
                function (Event $event, $dispatcher) use ($pr) {
                    $pr = $pr->privateResources->toArray();
                    $pr['index'] = [];
                    $pr['session'] = [];
                    if (!in_array($dispatcher->getControllerName(), array_keys($pr))) {
                        $dispatcher->setNamespaceName('');
                    }
                }
            );
            $dispatcher = new Dispatcher();
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        };
    }
}
