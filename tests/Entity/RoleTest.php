<?php

namespace App\Tests\Entity;

use App\Entity\Role;
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
}