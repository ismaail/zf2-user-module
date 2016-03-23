<?php
namespace User\Authentication;

use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\StorageInterface;
use User\Entity\UserEntityInterface;

/**
 * Class Auth
 * @package User\Authentication
 */
class Auth
{
    /**
     * @var AuthenticationService
     */
    protected $service;

    /**
     * @var \Zend\Authentication\Adapter\AdapterInterface|\DoctrineModule\Authentication\Adapter\ObjectRepository
     */
    protected $adapter;

    /**
     * Set authentication service
     *
     * @param AuthenticationService $service
     */
    public function setAuthenticationService(AuthenticationService $service)
    {
        $this->service = $service;
        $this->adapter = $service->getAdapter();
    }

    /**
     * Set storage
     *
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->service->setStorage($storage);
    }

    /**
     * Get storage
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->service->getStorage();
    }

    /**
     * Set identity value
     *
     * @param $value
     */
    public function setIdentityValue($value)
    {
        $this->adapter->setIdentity($value);
    }

    /**
     * Set credential
     *
     * @param $value
     */
    public function setCredentialValue($value)
    {
        $this->adapter->setCredential($value);
    }

    /**
     * Hash a string using Crypt Blowfish
     * using email sha1 hash for salt
     *
     * @param UserEntityInterface $user
     * @param string $password          The string to hash
     *
     * @return string                   encrypted password
     *
     * @throws \Exception               If CRYPT BLOWFISH is not supported
     */
    public static function hashPassword(UserEntityInterface $user, $password)
    {
        if (! defined('CRYPT_BLOWFISH') || ! CRYPT_BLOWFISH) {
            throw new \Exception('Crypt Blowfish is not supported', 1);
        }

        $salt         = substr(sha1($user->getEmail()), 6, 22);
        $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12, 'salt' => $salt]);

        return $passwordHash;
    }

    /**
     * Athenticate
     *
     * @param array $excludes
     * @param bool $rememberMe
     *
     * @return \Zend\Authentication\Result
     */
    public function authenticate(array $excludes = null, $rememberMe = false)
    {
        $authResult = $this->service->authenticate();

        if ($authResult->isValid()) {
            $identity = $authResult->getIdentity();

            // exclude fields in session storage
            if ($excludes) {
                foreach ($excludes as $item) {
                    $identity->{'set'.ucfirst($item)}(null);
                }
            }

            $this->service->getStorage()->write($identity);

            if ($rememberMe) {
                $this->service->getStorage()->rememberMe();
            }
        }

        return $authResult;
    }

    /**
     * Checkc if auth service has an identity
     *
     * @return bool
     */
    public function hasIdentity()
    {
        return $this->service->hasidentity();
    }

    /**
     * Get the auth service identity
     *
     * @return \User\Entity\User|null
     */
    public function getIdentity()
    {
        return $this->service->getIdentity();
    }

    /**
     * clearIdentity
     */
    public function clearIdentity()
    {
        $this->service->clearIdentity();
    }
}
