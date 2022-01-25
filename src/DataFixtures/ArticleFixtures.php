<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use Faker;
use App\Entity\Avis;
use App\Entity\Categorie;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        //Créer 3 catégories fakées
       
            //Créer entre 4 et 6 articles

            for ($j=1; $j<= mt_rand(4,6); $j++){
                $article = new Article();

                $article->setnom($faker->sentence())
                        ->setdescription($faker->sentence())
                        ->setprix($faker->numberBetween($min = 1, $max = 100))
                        ->setCategorie($manager->getRepository(Categorie::class)->find(mt_rand(1,3)));
                        
                $manager->persist($article);

                // On donne des commentaires à l'article
                for($k=1;$k<= mt_rand(4,10);$k++){
                    $avis = new Avis();

                    $startDate=$faker->dateTimeBetween('+0 days', '+1 months');
                    $startDateClone=clone $startDate; 
                    // $dateTime=new \DateTime($startDate, $startDateClone->modify('+5 hours'));
            

                    $avis->setAuteur($faker->name)
                         ->setContenue($faker->sentence())
                         ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween($startDate, $startDateClone->modify('+5 hours'))) )
                         ->setArticle($article);

                    $manager->persist($avis);
                }

            }
        


        $manager->flush();
    }
}
