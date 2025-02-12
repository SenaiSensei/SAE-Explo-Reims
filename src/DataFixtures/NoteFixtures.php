<?php

namespace App\DataFixtures;

use App\Factory\NoteFactory;
use App\Factory\PlaceFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NoteFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        NoteFactory::createMany(100, function () {
            return [
                'user' => UserFactory::random(),
                'place' => PlaceFactory::random(),
            ];
        });
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PlaceFixture::class,
        ];
    }
}
