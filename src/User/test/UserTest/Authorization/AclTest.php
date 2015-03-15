<?php
namespace UserTest\Authorization;

use PHPUnit_Framework_TestCase;
use User\Authorization\Acl;

class AclTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \User\Authorization\Exception\AclException
     */
    public function testThrowExceptionIfNoConfigIsProvided()
    {
        new Acl([]);
    }

    /**
     * @expectedException \User\Authorization\Exception\AclException
     */
    public function testThrowExceptionIfNoConfigRolesProvided()
    {
        new Acl([
            'roles' => [],
        ]);
    }

    /**
     * @expectedException \User\Authorization\Exception\AclException
     */
    public function testThrowExceptionIfNoConfigResourcesProvided()
    {
        new Acl([
            'resources' => [],
        ]);
    }

    public function testIsAllowedRules()
    {
        $acl = new Acl($this->getAclConfig());

        $this->assertTrue(
            $acl->isAllowed('GUEST', 'Acme::FirstController'),
            'GUEST is allowed inAcme::SecondController'
        );

        $this->assertTrue(
            $acl->isAllowed('GUEST', 'Acme::SecondController'),
            'GUEST is allowed in Acme::SecondController'
        );

        $this->assertFalse(
            $acl->isAllowed('GUEST', 'Acme'),
            'GUEST is not allowed in Acme namespace'
        );

        $this->assertTrue(
            $acl->isAllowed('MEMBER', 'Acme'),
            'MEMBER is allowed in Acme namespace'
        );
    }

    private function getAclConfig()
    {
        $config = array(
            'default_role' => 'GUEST',
            'roles'        => [
                'GUEST'  => null,
                'MEMBER' => 'GUEST',
            ],
            'resources' => [
                'deny' => [
                    'all' => [
                        'all' => 'GUEST',
                    ]
                ],
                'allow' => [
                    'Acme::FirstController' => [
                        'all' => 'GUEST',
                    ],
                    'Acme::SecondController' => [
                        'all' => 'GUEST',
                    ],
                    'Acme' => [
                        'all' => 'MEMBER',
                    ]
                ],
            ],
        );

        return $config;
    }
}
