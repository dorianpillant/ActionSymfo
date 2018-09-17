<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleController extends Controller
{
    /**
     * @Route("/article", name="article")
     */
    public function index(ArticleRepository $repo)
    {
        //je veux discuter avec doctrine et je veux un repository(repository selectione une table de la bdd)
        //je veux le repository qui gère l'entité Article
        // :: ->fournit un moyen d'accéder aux membres static ou constant, ainsi qu'aux propriétés ou méthodes surchargées d'une classe.
        //si j'utilise Article je dois faire le chemin au dessus dans le namespace
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        $articles = $repo->findAll();

        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
            'articles' => $articles,
        ]);
    }



    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('article/home.html.twig');

    }

    //add permet d'ajouter des champs
    /**
    * @Route("/article/new", name="article_create")
    * @Route("/article/{id}/edit", name="article_edit")
    */
    public function form(Article $article = null, Request $request, ObjectManager $manager)
    {
        if(!$article) {
            $article = new Article();
        }
        


       /* $form = $this->createFormBuilder($article)
                     ->add('title')
                     ->add('content')
                     ->add('image')
                     ->getForm();
        */

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            if($article->getId()){
               $article->setCreatedAt(new \DateTime()); 
            }
            

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('article_show', ['id' => $article->getid()]);
        }

        return $this->render('article/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);

    }


    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);

        $article = $repo->find($id);

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }






}
