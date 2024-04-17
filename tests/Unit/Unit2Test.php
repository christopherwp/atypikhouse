<?php

namespace App\Tests\Unit;

use App\Entity\User;
use App\Entity\House;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class Unit2Test extends TestCase
{
    public function testHouse(): void
    {   
        $user = new User();
        $user->setRoles(['ROLE_PROPRIO'])
             ->setEmail('proprio@example.com')
             ->setUsername('proprio')
             ->setPassword('admin1234');
        
        $category = new Category();
        $category->setName('Maison');

        $house = new House();
        $house->setTitle("Maison de vacances")
            ->setLatitude(45.0)
            ->setLongitude(3.0)
            ->setAddress("123 Rue de Paris")
            ->setCapacity(4)
            ->setNumRooms(2)
            ->setNumBedrooms(1)
            ->setNumBathrooms(1)
            ->setDailyPrice(100)
            ->setDescription("Belle maison de vacances")
            ->setCategory($category); 
            
            $this->assertEquals("Maison de vacances", $house->getTitle());
            $this->assertEquals(45.0, $house->getLatitude());
            $this->assertEquals(3.0, $house->getLongitude());
            $this->assertEquals("123 Rue de Paris", $house->getAddress());
            $this->assertEquals(4, $house->getCapacity());
            $this->assertEquals(2, $house->getNumRooms());
            $this->assertEquals(1, $house->getNumBedrooms());
            $this->assertEquals(1, $house->getNumBathrooms());
            $this->assertEquals(100, $house->getDailyPrice());
            $this->assertEquals("Belle maison de vacances", $house->getDescription());
            


    }
}
