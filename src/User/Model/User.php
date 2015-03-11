<?php
namespace User\Model;

use Application\Model\AbstractModel;
use Application\Model\Exception\ModelException;

/**
 * Class User
 * @package  User\Model
 */
class User extends AbstractModel
{
    protected $entityName = 'User\Entity\User';

    /**
     * Find user by id
     *
     * @param integer $userId
     * @param string $role
     *
     * @return \User\Entity\User|null
     */
    public function findById($userId, $role = null)
    {
        $cacheKey = sprintf('user_%d', $userId);

        $user = $this->getCacheItem($cacheKey);

        if (false !== $user) {
            return $user;
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
           ->from($this->getEntityName(), 'u')
           ->where('u.id = :user_id')
           ->setParameter('user_id', $userId)
           ;

        if (null !== $role) {
            $qb->andWhere('u.role = :role')
               ->setParameter('role', $role);
        }

        $user = $qb->getQuery()->getSingleResult();

        $this->setCacheItem($cacheKey, $user, ['users', sprintf('user_%d', $userId)]);

        return $user;
    }

    /**
     * Create new user
     *
     * @param array $data
     *
     * @return \User\Entity\User
     *
     * @throws ModelException
     */
    public function create(array $data)
    {
        try {
            $this->em->beginTransaction();

            $user = new \User\Entity\User();
            $user->populate($data);

            $user->setPassword(\User\Authentication\Auth::hashPassword($user, $data['password']));

            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();

            return $user;

        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw new ModelException("Failed creating new user", 0, $e);
        }
    }

    /**
     * Changing user role
     *
     * @param \User\Entity\User $user
     * @param string $role
     *
     * @throws ModelException
     */
    public function changeRole(\User\Entity\User $user, $role)
    {
        if ($role === $user->getRole()) {
            return;
        }

        try {
            $this->em->beginTransaction();

            $user->setRole($role);
            $user->setToken(null);

            $this->em->flush();
            $this->em->commit();

        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw new ModelException("Failed activating user", 0, $e);
        }
    }

    /**
     * Update user
     *
     * @param $userId
     * @param $data
     *
     * @throws \Application\Model\Exception\ModelException
     */
    public function update($userId, $data)
    {
        /** @var \User\Entity\User $user */
        $user = $this->findOneById($userId);

        try {
            $this->em->beginTransaction();

            $user->setFullname($data['fullname']);
            $user->setEmail($data['email']);

            $user->setPassword(\User\Authentication\Auth::hashPassword($user, $data['password']));

            $this->em->flush();
            $this->em->commit();

            $this->clearCacheByTags(array(sprintf('user_%d', $userId)));

        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw new ModelException("Error updating user profile", 0, $e);
        }
    }

    /**
     * @param integer $userId
     * @param $newPassword
     *
     * @throws \Exception
     */
    public function updatePassword($userId, $newPassword)
    {
        $user = $this->findOneById($userId);

        $user->setPassword(\User\Authentication\Auth::hashPassword($user, $newPassword));

        $this->em->flush();
    }

    /**
     * Check user password
     *
     * @param $userId
     * @param $password
     *
     * @return bool
     */
    public function checkPassword($userId, $password)
    {
        $user = $this->findById($userId);

        $passwordHash = \User\Authentication\Auth::hashPassword($user, $password);

        $qb = $this->em->createQueryBuilder();
        $qb->select('partial u.{id}')
           ->from($this->getEntityName(), 'u')
           ->where('u.id = :user_id')
           ->andWhere('u.password = :password')
           ->setParameter('user_id', $userId)
           ->setParameter('password', $passwordHash)
           ;

        return 1 === count($qb->getQuery()->getScalarResult());
    }
}
