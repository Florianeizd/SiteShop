<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=10; $i++){
            $article = new Article();
            $article->setnom("Nom de l'article n°$i")
                    ->setdescription("<p>Description de l'article n°$i</p>")
                    ->setprix("Prix de l'article n°$i");
                    
            $manager->persist($article);

        }

        $manager->flush();
    }
}
