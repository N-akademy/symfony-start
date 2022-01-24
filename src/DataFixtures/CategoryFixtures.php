<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = [
    'Action',
    'Aventure',
    'Animation',
    'Fantastique',
    'Horreur'
    ];

    public const CATEGORY_REFERENCE = 'Horreur';

    public function load(ObjectManager $manager)
    {
        foreach (self::CATEGORIES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);

            if ($categoryName === 'Horreur') {
                $this->addReference(self::CATEGORY_REFERENCE, $category);
            }
        }

        $manager->flush();

    }
}
