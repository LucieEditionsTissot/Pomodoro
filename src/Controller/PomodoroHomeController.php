<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PomodoroHomeController extends AbstractController
{
    #[Route('/', name: 'pomodoro_home')]
    public function home(Request $request, EntityManagerInterface $entityManager, TaskController $taskController): Response
    {
        $task = new Task();
        $taskForm = $this->createForm(TaskType::class, $task);
        $tasks = $entityManager->getRepository(Task::class)->findAll();

        return $this->render('pomodoro_home/index.html.twig', [
            'taskForm' => $taskForm->createView(),
            'tasks' => $tasks,
        ]);
    }
}
