<?php
namespace User;

use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use User\Authentication\AuthStorage;
use User\Authorization\Acl;
use User\Authorization\Exception\AclException;

/**
 * Class Module
 * @package User
 */
class Module
{
    /**
     * @param MvcEvent $event
     */
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager  = $event->getApplication()->getEventManager();
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            array($this, 'setupAclAuth'),
            100
        );
    }

    /**
     * @param MvcEvent $event
     */
    public function setupAclAuth(MvcEvent $event)
    {
        $config = $event->getApplication()->getServiceManager()->get('config');
        $role   = $config['acl']['default_role'];

        // Get the namespace to use with auth
        $storageNamespace = $config['auth_storage']['default'];
        $routeParams      = $this->getRouteParams($event->getRouteMatch());
        if (isset($config['auth_storage'][$routeParams['namespace']])) {
            $storageNamespace = $config['auth_storage'][$routeParams['namespace']];
        }

        /** @var \User\Authentication\Auth $authService */
        $authService = $event->getApplication()->getServiceManager()->get('authService');
        $authStorage = new AuthStorage($storageNamespace);
        $authService->setStorage($authStorage);

        // override role if user has identity
        if ($authService->hasIdentity()) {
            $role = $authService->getIdentity()->getRole();
        }

        $isAllowed = $this->isAclAllowed($config['acl'], $role, $routeParams);
        $this->handleRedirection($isAllowed, $event);
    }

    /**
     * @param array $config
     * @param string $role
     * @param array $routeParams
     *
     * @return bool
     *
     * @throws AclException
     *
     * @todo Use cache
     */
    public function isAclAllowed($config, $role, $routeParams)
    {
        if (! array_key_exists($role, $config['roles'])) {
            throw new AclException(sprintf("Role %s don't exist in the allowed roles list", $role));
        }

        $acl = new Acl($config);

        if (! $acl->hasRole($role)) {
            throw new AclException(sprintf('Role %s not valid', $role));
        }

        $isAllowed = false;

        if ($acl->hasResource($routeParams['controller'])
            && $acl->isAllowed($role, $routeParams['controller'], $routeParams['action'])
        ) {
            $isAllowed = true;

        } elseif ($acl->hasResource($routeParams['namespace'].'::'.$routeParams['controllerName'])
           && $acl->isAllowed($role, $routeParams['namespace'].'::'.$routeParams['controllerName'])
        ) {
            $isAllowed = true;

        } elseif ($acl->hasResource($routeParams['namespace']) && $acl->isAllowed($role, $routeParams['namespace'])) {
            $isAllowed = true;

        } elseif ($acl->isAllowed($role)) {
            $isAllowed = true;
        }

        return $isAllowed;
    }

    /**
     *
     * Get route params (namespace, controller and action)
     *
     * @param \Zend\Mvc\Router\RouteMatch $routeMatch
     *
     * @return array
     */
    protected function getRouteParams($routeMatch)
    {
        $params = array();

        $params['namespace']  = substr(
            $routeMatch->getParam('__NAMESPACE__'),
            0,
            strpos($routeMatch->getParam('__NAMESPACE__'), '\\')
        );

        $params['controllerName'] = sprintf(
            '%sController',
            substr($routeMatch->getParam('controller'), strrpos($routeMatch->getParam('controller'), '\\') + 1)
        );

        $params['controller'] = $routeMatch->getParam('controller');
        $params['action']     = $routeMatch->getParam('action');

        return $params;
    }

    /**
     * Handle redirection based on role
     *
     * @param bool $isAllowed
     * @param MvcEvent $event
     *
     * @return \Zend\Http\PhpEnvironment\Response
     */
    protected function handleRedirection($isAllowed, MvcEvent $event)
    {
        $config = $event->getApplication()->getServiceManager()->get('config');
        $authService = $event->getApplication()->getServiceManager()->get('authService');

        if (! $isAllowed) {
            if ($authService->hasIdentity()
                && $authService->getIdentity()->getRole() !== 'ADMIN'
            ) {
                // Change routeMatch to Deny page
                $event->setRouteMatch(new RouteMatch(array(
                    'controller' => $config['acl']['deny_page']['controller'],
                    'action'     => $config['acl']['deny_page']['action'],
                )));
                $event->getResponse()->setStatusCode(403);

            } else {
                // Change to login page
                $event->setRouteMatch(new RouteMatch(array(
                    'controller' => $config['redirect']['login_page']['controller'],
                    'action'     => $config['redirect']['login_page']['action'],
                )));
            }
        }
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
