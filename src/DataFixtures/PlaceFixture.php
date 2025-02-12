<?php

namespace App\DataFixtures;

use App\Factory\AccessibilityFactory;
use App\Factory\CategoryFactory;
use App\Factory\ImageFactory;
use App\Factory\PlaceFactory;
use App\Factory\TimeTableFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PlaceFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Place without TimeTable
        PlaceFactory::createMany(3, function () {
            return [
                'accessibility' => AccessibilityFactory::randomRange(1, 3),
                'category' => CategoryFactory::random(),
            ];
        });
        // Place without Accessibility
        for ($i = 0; $i < 3; ++$i) {
            $place = PlaceFactory::CreateOne(['category' => CategoryFactory::random()]);
            for ($j = 0; $j < 5; ++$j) {
                TimeTableFactory::createOne(['place' => $place]);
            }
        }
        // places with all
        for ($i = 0; $i < 5; ++$i) {
            $place = PlaceFactory::CreateOne(['accessibility' => AccessibilityFactory::randomRange(2, 5), 'category' => CategoryFactory::random()]);
            for ($j = 0; $j < 5; ++$j) {
                TimeTableFactory::createOne(['place' => $place]);
            }
            ImageFactory::createMany(3, ['place' => $place]);
        }
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            AccessibilityFixture::class,
        ];
    }
}
