<?php

namespace App\DataFixtures;

use App\Factory\ItineraryFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ItineraryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        ItineraryFactory::createMany(10, function () {
            return ['user' => UserFactory::random()];
        });
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
