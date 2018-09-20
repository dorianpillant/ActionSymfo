<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends Controller
{
   /**
     * @Route("/inscription", name="security_registration")
     */ 
   public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){

        $user = new User();
        //on relit les champs du formulaire au champ de l'utilisateur
        $form = $this->createForm(registrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');

        }

        //je veux affichier le formulaire et y ajouer des variables dans le tableau si dessous qu'il puisse utiliser
        return $this->render('security/registration.html.twig', [
            'form' => $form->createView() 
        ]);


   }

   /**
   * @Route("/connexion", name="security_login")
   */
   public function login(){
       return $this->render('security/login.html.twig');
   }

   /**
   * @Route("/deconnexion", name="security_logout")
   */
   public function logout(){}
    //ici il me faut juste une route
   
}
