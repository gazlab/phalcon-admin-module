<?php

namespace Gazlab\Admin;

use Gazlab\Admin\Libraries\Vokuro\Acl;
use Phalcon\DiInterface;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config;
use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Flash\Session;
use Phalcon\Logger\Adapter\File;
use Phalcon\Mvc\Dispatcher;
use WyriHaximus\HtmlCompress\Factory;

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
            'Gazlab\Admin\Libraries'      => __DIR__ . '/library/',
        ]);

        $loader->registerDirs([
            APP_PATH . '/modules/' . $di['router']->getModuleName() . '/controllers',
            APP_PATH . '/common/models'
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

            $eventsManager = new Manager();
            $eventsManager->attach(
                'view:afterRender',
                function (Event $event, $view) {
                    $parser = Factory::construct();
                    $compressedHtml = $parser->compress($view->getContent());
                    $view->setContent($compressedHtml);
                }
            );

            $view = new View();
            $view->setViewsDir($config->get('application')->viewsDir);

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
            $eventsManager = new Manager();
            $logger = $this->getShared('logger');

            $eventsManager->attach(
                'db:beforeQuery',
                function ($event, $connection) use ($logger) {
                    $logger->info(
                        $connection->getSQLStatement()
                    );
                }
            );

            $config = $this->getConfig();

            $dbConfig = $config->database->toArray();

            $dbAdapter = '\Phalcon\Db\Adapter\Pdo\\' . $dbConfig['adapter'];
            unset($config['adapter']);

            $connection = new $dbAdapter($dbConfig);
            $connection->setEventsManager($eventsManager);

            return $connection;
        };

        $di['breadcrumbs'] = function () {
            $breadcrumbs = new \Phalcon\Breadcrumbs;
            $breadcrumbs->setSeparator('');
            $breadcrumbs->setLastNotLinked(true);
            $breadcrumbs->setTemplate(
                '<li class="breadcrumb-item"><a href="{{link}}">{{icon}}{{label}}</a></li>', // linked
                '<li class="breadcrumb-item active">{{icon}}{{label}}</li>',         // not linked
                '<i class="fa fa-dashboard"></i>'                    // first icon
            );

            return $breadcrumbs;
        };

        $di['gravatar'] =  function () {
            $gravatar = new \Phalcon\Avatar\Gravatar([]);
            $gravatar->setDefaultImage('retro')
                ->setSize(160)
                ->setRating(\Phalcon\Avatar\Gravatar::RATING_PG);
            $gravatar->enableSecureURL();
            return $gravatar;
        };

        $di['flashSession'] = function () {
            return new Session([
                'error'   => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning'
            ]);
        };

        /**
         * Setup the private resources, if any, for performance optimization of the ACL.  
         */
        $di['AclResources'] = function () {
            $sql = 'SELECT resource, actions FROM ga_permission';
            $permissions = $this->getShared('db')->fetchAll($sql);

            $pr = [];
            $path = __DIR__ . '/config/privateResources.php';
            if (is_readable($path)) {
                $pr = include $path;
                $pr = $pr->privateResources->toArray();
            }
            foreach ($permissions as $permission) {
                foreach (json_decode($permission['actions'], true) as $action) {
                    $pr[$permission['resource']][] = $action;
                }
            }
            return $pr;
        };
        /**
         * Access Control List
         * Reads privateResource as an array from the config object.
         */
        $di['acl'] = function () {
            $acl = new Acl();
            $pr = $this->getShared('AclResources');
            $acl->addPrivateResources($pr);
            return $acl;
        };

        $di['dispatcher'] = function () {
            // Create an event manager
            $eventsManager = new Manager();
            // Attach a listener for type 'dispatch'
            $eventsManager->attach(
                'dispatch:beforeDispatchLoop',
                function (Event $event, $dispatcher) {
                    if (!in_array($dispatcher->getControllerName(), ['index', 'session', 'users'])) {
                        $dispatcher->setNamespaceName('');
                    }
                }
            );
            $dispatcher = new Dispatcher();
            // Bind the eventsManager to the view component
            $dispatcher->setEventsManager($eventsManager);
            return $dispatcher;
        };

        $di['logger'] = function () {
            $dir = APP_PATH . '/logs';
            if (!is_dir($dir)) {
                mkdir($dir);
            }
            return new File($dir . '/' . date('Ymd') . '.log');
        };
    }
}
