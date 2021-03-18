<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($j = 0; $j < 5; $j++){
            $cat = (new Category())
                ->setTitle($faker->sentence(3))
                ->setSlug($faker->sentence(3));
            $manager->persist($cat);

             for ($k = 0; $k < 3; $k++) {
                 $product = (new Product())
                     ->setTitle($faker->sentence(3))
                     ->setDescription($faker->paragraph())
                     ->setPrice($faker->randomFloat(2, 5, 30))
                     ->setPound($faker->numberBetween(500, 4000))
                     ->setSlug($faker->sentence(3))
                     ->setCategory($cat)
                     ->setCreatedAt($faker->dateTimeBetween('-2 weeks','now'));
                 $manager->persist($product);
             }
        }


                $manager->flush();
    }
}
