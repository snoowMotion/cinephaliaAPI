<?php

namespace App\Tests\Entity;

use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(User::class)]
class UserTest extends TestCase
{
    #[Test]
    public function testGetSetId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    #[Test]
    public function testGetSetEmail()
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertSame($email, $user->getEmail());
    }

    #[Test]
    public function testGetSetRoles()
    {
        $user = new User();
        $role = new Role();
        $user->addRole($role);
        $this->assertCount(1, $user->getRoles());
        $user->removeRole($role);
        $this->assertCount(0, $user->getRoles());
    }

    #[Test]
    public function testGetSetPassword()
    {
        $user = new User();
        $password = 'password123';
        $user->setPassword($password);
        $this->assertSame($password, $user->getPassword());
    }

    #[Test]
    public function testGetSetNom()
    {
        $user = new User();
        $nom = 'Doe';
        $user->setNom($nom);
        $this->assertSame($nom, $user->getNom());
    }

    #[Test]
    public function testGetSetPrenom()
    {
        $user = new User();
        $prenom = 'John';
        $user->setPrenom($prenom);
        $this->assertSame($prenom, $user->getPrenom());
    }

    #[Test]
    public function testEraseCredentials()
    {
        $user = new User();
        $user->eraseCredentials();
        $this->assertTrue(true); // No sensitive data to clear, just ensure the method exists
    }

    #[Test]
    public function testGetUserIdentifier()
    {
        $user = new User();
        $email = 'test@example.com';
        $user->setEmail($email);
        $this->assertSame($email, $user->getUserIdentifier());
    }
}
