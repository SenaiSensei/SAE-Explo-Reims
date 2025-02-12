<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    /**
     * Loads categories from DataFixtures/data/Category.json and persists then in the database.
     */
    public function load(ObjectManager $manager): void
    {
        $categories = json_decode(file_get_contents(__DIR__.'/data/Category.json'), true);
        CategoryFactory::createSequence($categories);
    }
}
