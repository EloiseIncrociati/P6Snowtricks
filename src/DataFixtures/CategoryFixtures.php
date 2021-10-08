<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            ['label' => 'Grabs'],
            ['label' => 'Rotations'],
            ['label' => 'Flips'],
            ['label' => 'Rotations désaxées'],
            ['label' => 'Slides'],
            ['label' => 'One foot tricks'],
            ['label' => 'Old school'],
        ];

        foreach ($categories as $category) {
            $category = new Category();
            $category->setLabel($category['label']);

            $manager->persist($category);
        }

        $manager->flush();
    }
}
