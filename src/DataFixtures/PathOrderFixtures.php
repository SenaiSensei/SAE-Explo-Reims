<?php

namespace App\DataFixtures;

use App\Factory\ItineraryFactory;
use App\Factory\PathOrderFactory;
use App\Factory\PlaceFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PathOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Parcours 1 avec 5 lieux
        for ($i = 1; $i < 4; ++$i) {
            $itinerary = ItineraryFactory::findOrCreate(['id' => $i]);
            for ($j = 1; $j <= 5; ++$j) {
                $place = PlaceFactory::findOrCreate(['id' => $j]);
                PathOrderFactory::createOne([
                    'itinerary' => $itinerary,
                    'place' => $place,
                    'position' => $i,
                ]);
            }
        }

        // Parcours 2 avec 3 lieux
        for ($i = 4; $i < 8; ++$i) {
            $itinerary = ItineraryFactory::findOrCreate(['id' => $i]);
            for ($j = 6; $j < 9; ++$j) {
                $place = PlaceFactory::findOrCreate(['id' => $j]);
                PathOrderFactory::createOne([
                    'itinerary' => $itinerary,
                    'place' => $place,
                    'position' => $i,
                ]);
            }
        }
    }

    public function getDependencies(): array
    {
        return [
            ItineraryFixtures::class,
            PlaceFixture::class,
        ];
    }
}
