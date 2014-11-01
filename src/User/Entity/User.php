<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use User\Entity\UserEntityInterface;

/**
 * Class User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity
 */
class User implements UserEntityInterface
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=150, nullable=true)
     */
    protected $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password_hash", type="string", length=25, nullable=false)
     */
    protected $password;

    /**
     * @var string $role
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     */
    protected $role = 'GUEST';

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
    * Convert the object to an array.
    *
    * @return array
    */
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
    * Populate from an array.
    *
    * @param array $data
    */
    public function populate($data = array())
    {
        $this->id       = (isset($data['id']))       ? $data['id']       : null;
        $this->email    = $data['email'];
        $this->password = (isset($data['password'])) ? $data['password'] : null;
        $this->role     = (isset($data['role']))     ? $data['role']     : 'GUEST';
    }
}
