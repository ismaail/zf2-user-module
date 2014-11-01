<?php
namespace User;

return array(
    // Controllers
    'controllers' => array(
        'invokables' => array(
            'User\Controller\User' => 'User\Controller\UserController',
        ),
    ),

    // PLugins
    'controller_plugins' => array (
        'invokables' => array(
            'authService' => 'User\Controller\Plugin\AuthService',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'AuthService' => 'User\Authentication\Factory\AuthServiceFactory',
        )
    ),

    // Doctrine config
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            ),
        ),
        'authentication' => array(
            'orm_default' => array(
                'object_manager'      => 'Doctrine\ORM\EntityManager',
                'identity_class'      => 'User\Entity\User',
                'identity_property'   => 'email',
                'credential_property' => 'password',
                'credential_callable' => 'User\Authentication\Auth::hashPassword',
            ),
        ),
    ),
);
