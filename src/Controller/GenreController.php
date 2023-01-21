<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\TypeGenreType;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genre", name="app_genre")
     */
    public function displayListGenre(EntityManagerInterface $entityManager): Response
    {
        //creation variable de recepetion objet
        $repository = $entityManager->getRepository(Genre::class);
        //appel de la fonction findAll pour avoir acces a l'ensemble des genres de la bd effectue une select * dans la table Genre
        $genre = $repository->findAll();
        return $this->render('genre/display.html.twig', [
            'list_genre' => $genre,
        ]);
    }

    /**
     * @Route("/genre/formulaire", name="app_genre_formulaire")
     */
    public function formGenre(EntityManagerInterface $entityManager,Request $request): Response {

        //creation d'un new objet genre
        $genre = new Genre();

        //creation du formulaire
        $formGenre = $this->createForm(TypeGenreType::class,$genre);

        $formGenre->handleRequest($request);
        /*condition qui va tester si le formulaire est en envoie et valid redirige vers une vue sinon
        va creer le formulaire*/
        if($formGenre->isSubmitted()&& $formGenre->isValid())
        {
            $genre = $formGenre->getData();

            //envoie du formulaire
            $entityManager->persist($genre);
            $entityManager->flush();
            //redirection vers une vue
            return $this->redirectToRoute('app_genre');
        }
        //creation de la vue
        return $this->render ('genre/formGenre.html.twig',[
            'formGenre'=>$formGenre->createView()
        ]);


    }
}
