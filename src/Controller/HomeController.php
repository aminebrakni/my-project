<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PropertyRepository;


class HomeController extends AbstractController {
    
   
    # Méthode
    /**
     * @Route("/",name="home").
     * $param PropertyRepository $repository
     * @return Response
     */
    public function index(PropertyRepository $repository): Response{

        $properties = $repository->findLatest(); 
        return $this->render('pages/home.html.twig',[
            'properties' => $properties
        ]);
    }
} 