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
}
