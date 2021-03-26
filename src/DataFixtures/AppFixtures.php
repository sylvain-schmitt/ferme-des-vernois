<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Unit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($l = 0; $l < 3; $l++) {
            $unit = (new Unit())
                ->setTitle($faker->sentence());
            $manager->persist($unit);
        }

        for ($j = 0; $j < 5; $j++) {
            $cat = (new Category())
                ->setTitle($faker->sentence(3));
            $manager->persist($cat);

            for ($k = 0; $k < 3; $k++) {
                $product = (new Product())
                    ->setTitle($faker->sentence(3))
                    ->setDescription($faker->paragraph())
                    ->setPrice($faker->randomFloat(2, 5, 30))
                    ->setPound($faker->numberBetween(500, 4000))
                    ->setCategory($cat)
                    ->setUnits($unit)
                    ->setActive(1)
                    ->setQuantity($faker->numberBetween(0, 40));

                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
