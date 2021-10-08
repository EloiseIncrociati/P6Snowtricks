<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    /** @var CategoryRepository $categoryRepository */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function load(ObjectManager $manager)
    {
        $tricksGroup = [
            ['category_label' => 'Grabs', 'tricks' => [
                ['title' => 'Mute', 'content' => 'Saisie de la carre frontside de la planche entre les deux pieds avec la main avant'],
                ['title' => 'Sad', 'content' => 'Saisie de la carre backside de la planche, entre les deux pieds, avec la main avant'],
                ['title' => 'Indy', 'content' => 'Saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière'],
                ['title' => 'Stalefish', 'content' => 'Saisie de la carre backside de la planche entre les deux pieds avec la main arrière'],
                ['title' => 'Tail grab', 'content' => 'Saisie de la partie arrière de la planche, avec la main arrière'],
                ['title' => 'Nose grab', 'content' => 'Saisie de la partie avant de la planche, avec la main avant'],
                ['title' => 'Japan', 'content' => 'Saisie de l\'avant de la planche, avec la main avant, du côté de la carre frontside'],
                ['title' => 'Seat belt', 'content' => 'Saisie du carre frontside à l\'arrière avec la main avant'],
                ['title' => 'Truck driver', 'content' => 'Saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)'],
            ]],
            ['category_label' => 'Rotations', 'tricks' => [
            ]],
            ['category_label' => 'Flips', 'tricks' => [
            ]],
            ['category_label' => 'Rotations désaxées', 'tricks' => [
            ]],
            ['category_label' => 'Slides', 'tricks' => [
            ]],
            ['category_label' => 'One foot tricks', 'tricks' => [
            ]],
            ['category_label' => 'Old school', 'tricks' => [
            ]]
        ];

        $categories = $this->categoryRepository->findAll();

        foreach ($categories as $category) {
            foreach ($tricksGroup as $trickGroup) {
                if ($trickGroup['category_label'] === $category->getLabel()) {
                    foreach ($trickGroup['tricks'] as $t) {
                        $trick = new Trick();
                        $trick->setTitle($t['title']);
                        $trick->setTitle(($t['title']));
                        $trick->setContent($t['content']);
                        $trick->setCategory($category);

                        $manager->persist($trick);
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class
        ];
    }
}
