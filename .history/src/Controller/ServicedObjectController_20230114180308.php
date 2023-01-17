<?php

namespace App\Controller;

use App\Entity\ServicedObject;
use App\Form\ServicedObjectType;
use App\Repository\ServicedObjectRepository;
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
    public function edit(Request $request, ServicedObject $servicedObject, ServicedObjectRepository $servicedObjectRepository): Response
    {
        //$sobject = new ServicedObject();
        $form = $this->createForm(ServicedObjectType::class, $servicedObject);
        $form->handleRequest($request);

        $em = $this->doctrine->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            
            dump($form->getData());
            exit();

            $servicedObjectRepository->save($servicedObject, true);

            

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
