<?php

namespace Gazlab\Admin;

use Gazlab\Admin\Acl;
use Phalcon\Avatar\Gravatar;
use Phalcon\Breadcrumbs;
use Phalcon\Config;
use Phalcon\DiInterface;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher as MvcDispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Adldap\Adldap;

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
            'Gazlab\Admin' => __DIR__ . '/library/',
        ]);

        $loader->registerDirs([
            APP_PATH . '/modules/' . $di['router']->getModuleName() . '/controllers/',
            APP_PATH . '/common/models/',
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

            $gazlabConfig = APP_PATH . '/modules/' . $di['router']->getModuleName() . '/config/gazlab.php';
            if (file_exists($gazlabConfig)) {
                $override = new Config(include $gazlabConfig);

                if ($config instanceof Config) {
                    $config->merge($override);
                } else {
                    $config = $override;
                }
            }
        }

        /**
         * Setting up the view component
         */
        $di['view'] = function () {
            $config = $this->getConfig();

            $view = new View();
            $view->setViewsDir(APP_PATH . '/modules/' . $this->get('router')->getModuleName() . '/views/');

            $view->registerEngines([
                '.volt' => 'voltShared',
                '.phtml' => PhpEngine::class,
            ]);

            $view->setMainView($config->get('application')->viewsDir . 'index');
            $view->setLayoutsDir($config->get('application')->viewsDir . 'layouts/');

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

            $eventsManager = new EventsManager();

            $logger = $this->getLogger();

            $eventsManager->attach(
                'db:beforeQuery',
                function ($event, $connection) use ($logger) {
                    $logger->info($connection->getSQLStatement() . ' ' . json_encode($connection->getSqlVariables()));
                }
            );

            $connection = new $dbAdapter($dbConfig);
            $connection->setEventsManager($eventsManager);

            return $connection;
        };

        $di->setShared('gravatar', function () {
            // Get Gravatar instance
            $gravatar = new Gravatar([]);

            // Setting default image, maximum size and maximum allowed Gravatar rating
            $gravatar->setDefaultImage('mm')
                ->setSize(160)
                ->setRating(Gravatar::RATING_G)
                ->enableSecureURL();

            return $gravatar;
        });

        /**
         * Setup the private resources, if any, for performance optimization of the ACL.
         */
        $di->setShared('AclResources', function () use ($config) {
            $sql = 'SELECT resource, action FROM ga_permissions';

            $permissions = $this->getShared('db')->fetchAll($sql);

            $pr = [];
            $pr = $config->privateResources->toArray();
            foreach ($permissions as $permission) {
                $pr[$permission['resource']][] = $permission['action'];
            }

            return $pr;
        });

        /**
         * Access Control List
         * Reads privateResource as an array from the config object.
         */
        $di->set('acl', function () {
            $acl = new Acl();
            $pr = $this->getShared('AclResources');
            $acl->addPrivateResources($pr);
            return $acl;
        });

        $di->set(
            'dispatcher',
            function () {
                $config = $this->getConfig();

                // Create an event manager
                $eventsManager = new EventsManager();

                // Attach a listener for type 'dispatch'
                $eventsManager->attach(
                    'dispatch:beforeDispatchLoop',
                    function (Event $event, $dispatcher) use ($config) {
                        $pr = $config->privateResources->toArray();
                        $pr['index'] = [];
                        $pr['session'] = [];

                        if (!in_array($dispatcher->getControllerName(), array_keys($pr))) {
                            $dispatcher->setNamespaceName('');
                        }
                    }
                );

                $dispatcher = new MvcDispatcher();

                // Bind the eventsManager to the view component
                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            },
            true
        );

        $di->setShared('breadcrumbs', function () {
            $breadcrumbs = new Breadcrumbs;
            $breadcrumbs->setSeparator('');
            $breadcrumbs->setLastNotLinked(true);

            return $breadcrumbs;
        });

        $di->set('flashSession', function () {
            return new FlashSession([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]);
        });

        $di->set('logger', function () {
            $dir = APP_PATH . '/logs';
            if (!is_dir($dir)) {
                mkdir($dir);
            }

            return new FileAdapter($dir . '/' . date('Ymd') . '.log');
        });

        $di->set('ldap', function () {
            $config = $this->getConfig()->ldap->toArray();
            unset($config['status']);
            $ldap = new Adldap($config);
            return $ldap;
        });
    }
}
