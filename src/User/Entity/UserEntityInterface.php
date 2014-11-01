<?php
namespace User\Entity;

/**
 * Class UserEntityInterface
 * @package User\Entity
 */
interface UserEntityInterface
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     *
     * @return string
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @param string $password
     *
     * @return string
     */
    public function setPassword($password);

    /**
     * @return string
     */
    public function getRole();

    /**
     * @param string $role
     *
     * @return string
     */
    public function setRole($role);
}
