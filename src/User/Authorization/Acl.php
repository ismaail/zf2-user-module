<?php
namespace User\Authorization;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 * Class Acl
 * @package User\Authentication
 *
 * This class is for loading ACL defined in a config
 */
class Acl extends ZendAcl
{
    /**
     * Default Role
     */
    const DEFAULT_ROLE = 'GUEST';

    /**
     * Constructor
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct($config)
    {
        if (! isset($config['roles'])
            || ! isset($config['resources'])
        ) {
            throw new \Exception('Invalid ACL Configuration, no rules/resources');
        }

        $roles = $config['roles'];

        if (! isset($roles[self::DEFAULT_ROLE])) {
            $roles[self::DEFAULT_ROLE] = '';
        }

        $this->addRoles($roles)
             ->addResources($config['resources']);
    }

    /**
     * Adds Roles to ACL
     *
     * @param array $roles
     *
     * @return Acl
     */
    protected function addRoles($roles)
    {
        foreach ($roles as $name => $parent) {
            if (! $this->hasRole($name)) {
                if (empty($parent)) {
                    $parent = array();
                } else {
                    $parent = explode(',', $parent);
                }

                $this->addRole(new Role($name), $parent);
            }
        }

        return $this;
    }

    /**
     * Adds Resources to ACL
     *
     * @param $resources
     *
     * @return Acl
     *
     * @throws \Exception
     */
    protected function addResources($resources)
    {
        foreach ($resources as $permission => $controllers) {
            foreach ($controllers as $controller => $actions) {
                if ($controller === 'all') {
                    $controller = null;
                } else {
                    if (! $this->hasResource($controller)) {
                        $this->addResource(new Resource($controller));
                    }
                }

                foreach ($actions as $action => $role) {
                    if ($action === 'all') {
                        $action = null;
                    }

                    if ($permission === 'allow') {
                        $this->allow($role, $controller, $action);
                    } elseif ($permission === 'deny') {
                        $this->deny($role, $controller, $action);
                    } else {
                        throw new \Exception('No valid permission defined: ' . $permission);
                    }
                }
            }
        }

        return $this;
    }
}
