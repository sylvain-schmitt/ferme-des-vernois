<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 15; $i++) {
            $product = (new Product())
                ->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph())
                ->setPrice($faker->randomFloat(2, 5, 30))
                ->setPound($faker->numberBetween(500, 4000))
                ->setSlug($faker->sentence(3))
                ->setCreatedAt($faker->dateTimeBetween('-2 weeks','now'));

                $manager->persist($product);
        }
                $manager->flush();
    }
}
