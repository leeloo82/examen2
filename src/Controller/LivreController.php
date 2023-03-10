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
            return $this->redirectToRoute('app_home');
        }

        //creation de la vue
        return $this->render('livre/formLivre.html.twig', [
            'formLivre' => $formLivre->createView()
        ]);
    }

    /**
     * @Route("/livre/description/{id}", name="app_livre_description")
     */
    public function displayDetailLivre($id, EntityManagerInterface $entityManager): response
    {

        //reception de l'objet de la la class repository
        $repository = $entityManager->getRepository(Livre::class);
        //appel de la fonction pour effectuer un select all dans la class repository find est par defaut
        $livre = $repository->find($id);
        //self::print_q($article);
        return $this->render('livre/detailLivre.html.twig', [
            'livre_detail' => $livre,
        ]);
    }
    /**
     * @Route("/livre/edit/{id}", name="app_livre_edit")
     *
     */
    public function edit(EntityManagerInterface $entityManager,Request $request,$id){

        //donner l'acces a la table livre
        $repository =$entityManager->getRepository(Livre::class);
        $livre = $repository->find($id);

        $formLivre = $this->createForm(LivreType::class, $livre);

        $formLivre->handleRequest($request);

        if ($formLivre->isSubmitted() && $formLivre->isValid()) {
            $livre = $formLivre->getData();

            //envoie du formulaire

            $entityManager->flush();
            //redirection vers une vue
            return $this->redirectToRoute('app_home');
        }

        //creation de la vue
        return $this->render('livre/editLivre.html.twig', [
            'formLivre' => $formLivre->createView()
        ]);
    }

    /**
     * @Route("/livre/delete/{id}", name="app_livre_delete")
     *
     */
    function delete(Livre $id){

        //recuperation de l'entity manager
        $repository = $this->getDoctrine()->getManager();
        //appel de la methode remove avec le parametre de suppression
        $repository->remove($id);
        //mise a jour de la data base
        $repository->flush();

        return $this->redirectToRoute('app_home');
    }

}
