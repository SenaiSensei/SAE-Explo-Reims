<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'email' => 'admin@example.com',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'password' => 'test',
            'roles' => ['ROLE_ADMIN'],
        ])->getPassword();
        UserFactory::createOne([
            'email' => 'root@example.com',
            'firstname' => 'Admin',
            'lastname' => 'Boss',
            'password' => 'test',
            'roles' => ['ROLE_ADMIN'],
        ])->getPassword();
        UserFactory::createOne([
            'email' => 'alain.proviste@example.com',
            'firstname' => 'Alain',
            'lastname' => 'Proviste',
            'password' => 'test',
            'roles' => ['ROLE_USER'],
        ])->getPassword();
        UserFactory::createMany(3);
    }
}
