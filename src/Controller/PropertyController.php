<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;

class PropertyController extends AbstractController
{   
    /**
     * @var PropertyRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;
    
    # Constructeur
    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
   
    # Méthode 
    /**
     * @Route("/biens",name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response{

        // permet de récupérer le bien qui a l'id 1 
        // $property = $this->repository->find(1);
        
        // permet de récupérer l'ensemble des biens qu'il y a dans la table -- renvoi un tableau
        // $property = $this->repository->findAll();
        
        // permet de passer en paramètre un tableau avec des critères -- ici récupérer tous les biens qui sont au 4e étage 
        // $property = $this->repository->findOneBy(['floor' => 4]);
        
        // fait appel à la méthode dans PropertyRepository pour trouver les biens qui ne sont pas vendu
        // $property = $this->repository->findAllVisible();

        // équivalent du var_dump
        //dump($property);
        
        // formulaire de recherche
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        // Pagination
        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            12 /*limit per page*/
        );

        //$properties = $this->repository->findAllVisibleQuery();
        return $this->render('property/index.html.twig',[
            'current_menu' => 'properties',
            'properties'   => $properties,
            'form' => $form->createView()
        ]);
    }

    # Méthode 
    /**
     * @Route("/biens/{slug}-{id}",name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @return Response
     */
    public function show(Property $property, string $slug): Response{

        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show',[
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ],301); 
        }
        return $this->render('property/show.html.twig',[
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }

}