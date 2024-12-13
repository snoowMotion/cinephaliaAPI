<?php

namespace App\Tests\Entity;

use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Role::class)]
class RoleTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $role = new Role();
        $this->assertNull($role->getId());
    }

    #[Test]
    public function testGetSetLibelle()
    {
        $role = new Role();
        $libelle = 'Admin';
        $role->setLibelle($libelle);
        $this->assertSame($libelle, $role->getLibelle());
    }

    #[Test]
    public function testGetSetUsers()
    {
        $role = new Role();
        $user = new User();
        $role->addUser($user);
        $this->assertCount(1, $role->getUsers());
        $role->removeUser($user);
        $this->assertCount(0, $role->getUsers());
    }
}
