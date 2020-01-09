<?php

namespace Gazlab\Admin\Libraries\Vokuro;

use Phalcon\Mvc\User\Component;
use Phalcon\Acl\Adapter\Memory as AclMemory;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
use Gazlab\Admin\Models\Profile as Profiles;

/**
 * Vokuro\Acl
 */
class Acl extends Component
{

    /**
     * The ACL Object
     *
     * @var \Phalcon\Acl\Adapter\Memory
     */
    private $acl;

    /**
     * The file path of the ACL cache file.
     *
     * @var string
     */
    private $filePath;

    /**
     * Define the resources that are considered "private". These controller => actions require authentication.
     *
     * @var array
     */
    private $privateResources = array();

    /**
     * Human-readable descriptions of the actions used in {@see $privateResources}
     *
     * @var array
     */
    private $actionDescriptions = [
        'index' => 'Access',
        'search' => 'Search',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'changePassword' => 'Change password'
    ];

    /**
     * Checks if a controller is private or not
     *
     * @param string $controllerName
     * @return boolean
     */
    public function isPrivate($controllerName)
    {
        $controllerName = strtolower($controllerName);
        return isset($this->privateResources[$controllerName]);
    }

    /**
     * Checks if the current profile is allowed to access a resource
     *
     * @param string $profile
     * @param string $controller
     * @param string $action
     * @return boolean
     */
    public function isAllowed($profile, $controller, $action)
    {
        return $this->getAcl()->isAllowed($profile, $controller, $action);
    }

    /**
     * Returns the ACL list
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function getAcl()
    {
        // Check if the ACL is already created
        if (is_object($this->acl)) {
            return $this->acl;
        }

        // Check if the ACL is in APC
        if (function_exists('apc_fetch')) {
            $acl = apc_fetch('vokuro-acl');
            if (is_object($acl)) {
                $this->acl = $acl;
                return $acl;
            }
        }

        $filePath = $this->getFilePath();

        // Check if the ACL is already generated
        if (!file_exists($filePath)) {
            $this->acl = $this->rebuild();
            return $this->acl;
        }

        // Get the ACL from the data file
        $data = file_get_contents($filePath);
        $this->acl = unserialize($data);

        // Store the ACL in APC
        if (function_exists('apc_store')) {
            apc_store('vokuro-acl', $this->acl);
        }

        return $this->acl;
    }

    /**
     * Returns the permissions assigned to a profile
     *
     * @param Profiles $profile
     * @return array
     */
    public function getPermissions(Profiles $profile)
    {
        $permissions = [];
        foreach ($profile->getPermissions() as $permission) {
            foreach ($permission->getActions() as $action) {
                $permissions[$permission->resource . '.' . $action] = true;
            }
        }
        return $permissions;
    }

    /**
     * Returns all the resources and their actions available in the application
     *
     * @return array
     */
    public function getResources()
    {
        return $this->privateResources;
    }

    /**
     * Returns the action description according to its simplified name
     *
     * @param string $action
     * @return string
     */
    public function getActionDescription($action)
    {
        if (isset($this->actionDescriptions[$action])) {
            return $this->actionDescriptions[$action];
        } else {
            return $action;
        }
    }

    /**
     * Rebuilds the access list into a file
     *
     * @return \Phalcon\Acl\Adapter\Memory
     */
    public function rebuild()
    {
        $acl = new AclMemory();

        $acl->setDefaultAction(\Phalcon\Acl::DENY);

        // Register roles

        $profiles = Profiles::find([
            'status_active = :active:',
            'bind' => [
                'active' => 1
            ]
        ]);
        foreach ($profiles as $profile) {
            $acl->addRole(new AclRole($profile->name));
        }

        foreach ($this->privateResources as $resource => $actions) {
            $acl->addResource(new AclResource($resource), $actions);
        }

        // Grant access to private area to role Users
        foreach ($profiles as $profile) {

            // Grant permissions in "permissions" model
            foreach ($profile->getPermissions() as $permission) {
                foreach ($permission->getActions() as $action) {
                    $acl->allow($profile->name, $permission->resource, $action);
                }
            }

            // Always grant these permissions
            $acl->allow($profile->name, 'users', 'profile');
            // $acl->deny($profile->name, 'session', 'signIn');
            // $acl->allow($profile->name, 'session', 'signOut');
        }

        $filePath = $this->getFilePath();

        if (touch($filePath) && is_writable($filePath)) {

            file_put_contents($filePath, serialize($acl));

            // Store the ACL in APC
            if (function_exists('apc_store')) {
                apc_store('vokuro-acl', $acl);
            }
        } else {
            $this->flash->error(
                'The user does not have write permissions to create the ACL list at ' . $filePath
            );
        }

        return $acl;
    }


    /**
     * Set the acl cache file path
     *
     * @return string
     */
    protected function getFilePath()
    {
        $dir = rtrim($this->config->application->cacheDir, '\\/') . '/acl';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        if (!isset($this->filePath)) {
            $this->filePath = $dir . '/data.txt';
        }

        return $this->filePath;
    }

    /**
     * Adds an array of private resources to the ACL object.
     *
     * @param array $resources
     */
    public function addPrivateResources(array $resources)
    {
        if (count($resources) > 0) {
            $this->privateResources = array_merge($this->privateResources, $resources);
            if (!is_object($this->acl)) {
                $this->acl = $this->rebuild();
            }
        }
    }
}
