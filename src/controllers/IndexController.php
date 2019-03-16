<?php
namespace Gazlab\Admin\Controllers;

class IndexController extends ControllerBase
{
    public function indexAction()
    {
        if ($this->getArea() === 'private') {
            // return $this->dispatcher->forward([
            //     'namespace' => '',
            //     'controller' => $this->resources[0]->menu[0],
            // ]);
            $i = 0;
            foreach ($this->resources as $index => $resource) {
                if (!isset($resource->menu['type'])) {
                    if ($i === 0) {
                        if (is_int($index)) {
                            $resource = $resource;
                        } else {
                            $resource = $resource[0];
                        }
                        break;
                    }
                }
            }

            return $this->response->redirect(join('/', [
                $this->router->getModuleName(),
                $resource->menu[0],
            ]));
        }
        $this->dispatcher->forward([
            'controller' => 'session',
            'action' => 'signIn',
        ]);
    }
}
