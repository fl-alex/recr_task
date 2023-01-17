<?php

namespace App\Controller;

use App\Entity\ServicedObject;
use App\Entity\File;
use App\Form\ServicedObjectType;
use App\Repository\ServicedObjectRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/objects')]
class ServicedObjectController extends AbstractController
{

    public function __construct(private ManagerRegistry $doctrine) {}

    #[Route('/', name: 'app_serviced_object_index', methods: ['GET'])]
    public function index(ServicedObjectRepository $servicedObjectRepository): Response
    {
        return $this->render('serviced_object/index.html.twig', [
            'serviced_objects' => $servicedObjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_serviced_object_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ServicedObjectRepository $servicedObjectRepository): Response
    {
        $servicedObject = new ServicedObject();
        $form = $this->createForm(ServicedObjectType::class, $servicedObject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $servicedObjectRepository->save($servicedObject, true);

            return $this->redirectToRoute('app_serviced_object_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serviced_object/new.html.twig', [
            'serviced_object' => $servicedObject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_serviced_object_show', methods: ['GET'])]
    public function show(ServicedObject $servicedObject): Response
    {
        return $this->render('serviced_object/show.html.twig', [
            'serviced_object' => $servicedObject,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_serviced_object_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, 
                        ServicedObject $servicedObject,
                        ): Response
    {
       
        $em = $this->doctrine->getManager();
        
        // get original existing filelinks from related entity
        $orignal_filelinks = new ArrayCollection();
        foreach ($servicedObject->getFiles() as $file) {
            $orignal_filelinks->add($file);
        }

        //make form from ServicedObjectType.php
        $form = $this->createForm(ServicedObjectType::class, $servicedObject);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // remove filelinks, if they removed by user from client interface
            // PS. This client functional not done
            foreach ($orignal_filelinks as $file) {
                if ($servicedObject->getFiles()->contains($file) === false) {
                    $em->remove($file);
                }
            }

            // saving object (with related - parameter cascade:['persist'] in entity)
            $em->persist($servicedObject);
            $em->flush(); 

            return $this->redirectToRoute('app_serviced_object_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('serviced_object/edit.html.twig', [
            'serviced_object' => $servicedObject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_serviced_object_delete', methods: ['POST'])]
    public function delete(Request $request, ServicedObject $servicedObject, ServicedObjectRepository $servicedObjectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$servicedObject->getId(), $request->request->get('_token'))) {
            $servicedObjectRepository->remove($servicedObject, true);
        }

        return $this->redirectToRoute('app_serviced_object_index', [], Response::HTTP_SEE_OTHER);
    }
}
