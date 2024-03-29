<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use App\Form\PropertyType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    private $em;

    public function __construct(PropertyRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
    * @Route("/admin", name="admin.property.index")
    * @return Response
    */
    public function index(): Response
    {
        $properties = $this->repository->findAll();
        return $this->render('admin/index.html.twig',compact('properties'));
    }

    /**
    * @Route("/admin/property/create", name="admin.property.new")
    * @param Request $request
    * @return Response
    */
    public function new(Request $request): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($property);
            $this->em->flush(); 
            $this->addFlash('success','Bien crée avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
    * @param Property $property
    * @param Request $request
    * @return Response
    */
    public function edit(Property $property, Request $request): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush(); 
            $this->addFlash('success','Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
    * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
    * @param Request $request
    * @param Property $property
    * @return Response
    */
    public function delete(Property $property, Request $request): Response
    {
        $this->em->remove($property); 
        $this->em->flush(); 
        $this->addFlash('success','Supprimé avec succès');

        // if($this->isCsrfTokenValid('delete', $property->getId(), $request->get('_token'))){
        //     $this->em->remove($property); 
        //     $this->em->flush(); 
        //     $this->addFlash('success','Supprimé avec succès');
        //     // return new Response('Supppression');
        // }
        
        return $this->redirectToRoute('admin.property.index');
    }
}