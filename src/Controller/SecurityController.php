<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
   /**
     * @Route("/inscription", name="security_registration")
     */ 
   public function registration(Request $request, ObjectManager $manager){

        $user = new User();
        //on relit les champs du formulaire au champ de l'utilisateur
        $form = $this->createForm(registrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

        }

        //je veux affichier le formulaire et y ajouer des variables dans le tableau si dessous qu'il puisse utiliser
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView() 
        ]);


   }
}
