<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i<3; $i++) {
            $subcat = (new SubCat())
                ->setTitle($faker->sentence(3))
                ->setSlug($faker->sentence(3));
                $manager->persist($subcat);

                for ($j = 0; $j < 2; $j++){
                    $cat = (new Category())
                        ->setTitle($faker->sentence(3))
                        ->setSlug($faker->sentence(3))
                        ->setSubCat($subcat);
                    $manager->persist($cat);
                }

            for ($k = 0; $k < 5; $k++) {
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
