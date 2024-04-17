<?php

namespace App\Tests\Unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class Unit1Test extends TestCase
{
    public function testUser(): void
    {   
        $user = new User;
        

        $user = new User();
        $user->setRoles(['ROLE_PROPRIO'])
             ->setEmail('proprio@example.com')
             ->setUsername('proprio')
             ->setPassword('admin1234');

        
        $this->assertTrue(in_array('ROLE_PROPRIO', $user->getRoles()));
        $this->assertEquals('proprio@example.com' ,$user->getEmail());
        $this->assertEquals('proprio', $user->getUsername());
        $this->assertEquals($user->getPassword(), 'admin1234');
    }
}
