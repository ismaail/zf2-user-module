<?php

namespace User\Authentication;

use Zend\Authentication\Storage\Session as StorageSession;

/**
 * Class AuthStorage
 * @package User\Authentication
 */
class AuthStorage extends StorageSession
{
    /**
     * Constructor
     *
     * @param null $namespace
     */
    public function __construct($namespace = null)
    {
        parent::__construct($namespace);
    }

    /**
     * Remember me
     *
     * @param int $time     0 seconds (Session only)
     */
    public function rememberMe($time = 0)
    {
        $this->session->getManager()->rememberMe($time);
    }

    /**
     * Forget me
     */
    public function forgetMe()
    {
        $this->session->getManager()->forgetMe();
    }
}
