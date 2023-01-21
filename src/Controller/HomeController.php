<?php

namespace App\Controller;

use App\Entity\Livre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $repository =$entityManager->getRepository(Livre::class);
        $livre = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'list_livre' => $livre,
        ]);
    }
}
