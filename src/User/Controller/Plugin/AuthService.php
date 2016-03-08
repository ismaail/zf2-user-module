<?php

namespace User\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

/**
 * Class AuthService
 * @package User\Controller\Plugin
 */
class AuthService extends AbstractPlugin implements ServiceLocatorAwareInterface
{
    /**
     * @var \User\Authentication\Auth
     */
    protected $authService;

    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * @return ServiceLocatorInterface|\Zend\Mvc\Controller\PluginManager
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * @return \User\Authentication\Auth
     */
    public function __invoke()
    {
        if (! $this->authService) {
            $this->authService = $this->getServiceLocator()->getServiceLocator()->get('AuthService');
        }

        return $this->authService;
    }
}
