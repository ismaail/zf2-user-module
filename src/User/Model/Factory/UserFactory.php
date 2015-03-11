<?php
namespace User\Model\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Di\Di;

/**
 * Class UserFactory
 * @package User\Model\Factory
 */
class UserFactory implements FactoryInterface
{
    /**
     * @param  ServiceLocatorInterface $sm
     *
     * @return User\Model\User
     */
    public function createService(ServiceLocatorInterface $sm)
    {
        $di = new Di();
        $di->instanceManager()->setParameters('User\Model\User', array(
            'em' => $sm->get('doctrine.entitymanager.orm_default'),
        ));

        $model = $di->get('User\Model\User');

        $model->setCache($sm->get('cache'));

        return $model;
    }
}
