<?php

namespace App\DataFixtures;

use App\Factory\ImageFactory;
use App\Factory\PlaceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        ImageFactory::createMany(2);
        ImageFactory::createOne(function () {return ['place' => PlaceFactory::random()]; });
        ImageFactory::createMany(5, ['place' => PlaceFactory::random()]);
        ImageFactory::createMany(3, ['place' => PlaceFactory::random()]);
    }

    public function getDependencies(): array
    {
        return [
            PlaceFixture::class,
        ];
    }
}
