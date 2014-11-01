<?php
namespace User\Authentication\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use User\Authentication\Auth;

/**
 * Class AuthServiceFactory
 * @package User\Authentication\Factory
 */
class AuthServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /*$config  = $sm->get('config');*/
        $service = new Auth();
        // $service->setAuthenticationService($serviceLocator->get('Zend\Authentication\AuthenticationService'));
        $service->setAuthenticationService($serviceLocator->get('doctrine.authenticationservice.orm_default'));

        // $service->setStorage($sm->get('AuthStorage'));
        /*$service->setSalt($config['authentication']['salt']);*/

        return $service;
    }
}
