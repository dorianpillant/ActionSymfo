<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = \Faker\Factory::create('fr_FR');

            //je veux créer 3 catégories fakées
            for($i = 1; $i <= 3; $i++) {
                $category = new Category();
                $category->setTitle($faker->sentence())
                        ->setDescription($faker->paragraph());

                $manager->persist($category);          

            

            //je veux créer entre 4 et 6 articles
            //mt_rand permet d'avoir un nombre au hasard
            for($j=1; $j<= mt_rand(4, 6); $j++){
                $article = new Article();

                // '.=' veut dire que j'ajoute quelque chose
                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setText($faker->text($maxNbChars = 15000))
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
              
                        
                $manager->persist($article);

                //on donne des commentaires à l'article
                for($k = 1 ; $k <= mt_rand(4, 10); $k++){
                    
                    $comment = new Comment();
                    
                    $content = '<p>' . join($faker->paragraphs(2),
                    '</p><p>') . '</p>';

                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;
                   
              
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setArticle($article);

                            $manager->persist($comment);
                }
            }
            // $product = new Product();
            // $manager->persist($product);

            $manager->flush();
        }
    }
}