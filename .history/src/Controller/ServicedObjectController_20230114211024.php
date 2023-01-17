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
    public function edit(Request $request, ServicedObject $servicedObject, File $files, ServicedObjectRepository $servicedObjectRepository): Response
    {
        $em = $this->doctrine->getManager();
        // save the records that are in the database first to compare them with the new one the user sent
        // make sure this line comes before the $form->handleRequest();
        $orignal_filelinks = new ArrayCollection();
        foreach ($servicedObject->getFiles() as $file) {
            $orignal_filelinks->add($file);
        }


        $form = $this->createForm(ServicedObjectType::class, $servicedObject);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            
            $responce = new Response(json_encode($servicedObject->getFiles()));
            $responce->send();
            
            exit();
            // get rid of the ones that the user got rid of in the interface (DOM)
            foreach ($orignal_filelinks as $file) {
                // check if the exp is in the $user->getExp()
//                dump($user->getExp()->contains($exp));
                if ($servicedObject->getFiles()->contains($file) === false) {
                    $em->remove($file);
                }
            }

            $em->persist($servicedObject);
            $em->persist($files);
            $em->flush();
        
            

            
            //$servicedObjectRepository->save($servicedObject, true);

            

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
