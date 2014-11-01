<?php

return array(
    'auth_storage' => array(
        'default' => 'Acme_Auth',
        'Admin'   => 'Admin_Auth',
    ),

    'acl' => array(
        'default_role' => 'GUEST',
        'roles' => array(
            'GUEST' => null,
            'ADMIN' => 'GUEST',
        ),
        'resources' => array(
            'deny' => array(
                'all' => array(
                    'all' => 'GUEST',
                ),
            ),
            'allow' => array(
                // Namespace\Controller\Action
                'Acme\Controller\Index' => array(
                    'all' => 'GUEST',
                ),
                // Namespace::ControllerName
                'Acme::IndexController' => array(
                    'all' => 'GUEST',
                ),
                // Namespace
                'Acme' => array(
                    'all' => 'GUEST',
                ),
                'all' => array(
                    'all' => 'ADMIN',
                ),
            ),
        ),
    ),

    'redirect' => array(
        'login_page' => array(
            'controller' => 'User\Controller\User',
            'action'     => 'login',
        ),
        'deny_page' => array(
            'controller' => 'User\Controller\User',
            'action'     => 'deny',
        ),
    ),
);
