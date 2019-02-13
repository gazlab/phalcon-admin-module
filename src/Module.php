<?php

namespace Gazlab\Admin;

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
            APP_PATH . '/modules/' . $di['router']->getModuleName() . '/controllers',
            APP_PATH . '/common/models',
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
        $di['view'] = function () use ($di) {
            $config = $this->getConfig();

            $view = new View();
            $view->setViewsDir(APP_PATH . '/modules/' . $di['router']->getModuleName() . '/views');

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

            $gravatar->setDefaultImage('mm')
                ->setSize(160)
                ->setRating(Gravatar::RATING_PG);

            return $gravatar;
        });

        $di->setShared(
            'dispatcher',
            function () {
                // Create an event manager
                $eventsManager = new EventsManager();

                // Attach a listener for type 'dispatch'
                $eventsManager->attach(
                    'dispatch:beforeDispatchLoop',
                    function (Event $event, $dispatcher) {
                        $ar = $this->getConfig()->adminResources->toArray();
                        if (!in_array($dispatcher->getControllerName(), array_keys($ar))) {
                            $dispatcher->setNamespaceName('');
                        }
                    }
                );

                $dispatcher = new MvcDispatcher();

                // Bind the eventsManager to the view component
                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            });

        $di->set('flashSession', function () {
            return new FlashSession([
                'error' => 'alert alert-danger',
                'success' => 'alert alert-success',
                'notice' => 'alert alert-info',
                'warning' => 'alert alert-warning',
            ]);
        });

        $di->setShared('breadcrumbs', function () {
            $breadcrumbs = new Breadcrumbs;
            $breadcrumbs->setLastNotLinked(true);
            $breadcrumbs->setSeparator('&nbsp;&raquo;&nbsp;');
            $breadcrumbs->setTemplate(
                '<li><a href="{{link}}">{{icon}}{{label}}</a></li>', // linked
                '<li class="active">{{icon}}{{label}}</li>', // not linked
                '' // first icon
            );

            return $breadcrumbs;
        });
    }
}
