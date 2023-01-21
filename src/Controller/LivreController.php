<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    /**
     * @Route("/livre/formulaire", name="app_livre_formulaire")
     */
    public function formLivre(EntityManagerInterface $entityManager,Request $request): Response
    {

        //creation d'un new objet livre
        $livre = new Livre();
        //creation de la date du jour dans l'objet livre
        $livre ->setDateAjout(new DateTime());
        //creation du formulaire
        $formLivre = $this->createForm(LivreType::class, $livre);

        $formLivre->handleRequest($request);

        if ($formLivre->isSubmitted() && $formLivre->isValid()) {
            $livre = $formLivre->getData();

            //envoie du formulaire
            $entityManager->persist($livre);
            $entityManager->flush();
            //redirection vers une vue
            //return $this->redirectToRoute('app_genre');
        }
        //creation de la vue
        //creation de la vue
        return $this->render('livre/formLivre.html.twig', [
            'formLivre' => $formLivre->createView()
        ]);
    }

}
