<?php

namespace App\DataFixtures;

use App\Factory\AccessibilityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccessibilityFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = json_decode(file_get_contents(__DIR__.'/data/Accessibility.json'), true);
        AccessibilityFactory::createSequence($category);
    }
}
