<?php

// src/Controller/TaskController.php
namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class TaskController extends AbstractController
{
    #[Route('/', name: 'app_files_index', methods: ['GET'])]
    public function new(Request $request): Response
    {
        $task = new Task();

        // фіктивний код - він тут просто для того, щоб Task мав якісь
        // інашке це не буде цікавим прикладом
        $tag1 = new Tag();
        $tag1->setName('tag1');
        $task->getTags()->add($tag1);
        $tag2 = new Tag();
        $tag2->setName('tag2');
        $task->getTags()->add($tag2);
        // кінець фіктивного коду

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ... проведіть обробку форми, на кшталт збереження сутностей Task і Tag
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }
}