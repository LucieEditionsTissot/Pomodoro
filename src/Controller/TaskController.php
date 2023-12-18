<?php
namespace App\Controller;

use App\Entity\PomodoroSession;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\PomodoroSessionRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/task/add-from-pomodoro/{pomodoroId}/{taskId?}', name: 'task_link_pomodoro', requirements: ['pomodoroId' => '\d+', 'taskId' => '\d+'])]
    public function addToPomodoro(Request $request, EntityManagerInterface $entityManager, int $pomodoroId, ?int $taskId = null): Response
    {
        // Hop, on récupère l'utilisateur actuel et la session Pomodoro.
        $user = $this->getUser();
        $pomodoroSession = $entityManager->getRepository(PomodoroSession::class)->find($pomodoroId);

        // Si la session n'existe pas ou n'appartient pas à l'utilisateur, on dit stop !
        if (!$pomodoroSession || $pomodoroSession->getUser() !== $user) {
            throw $this->createAccessDeniedException('Hey, c\'est pas ta session ça !');
        }

        // On cherche la tâche si on a un ID, sinon on en crée une nouvelle.
        $task = $taskId ? $entityManager->getRepository(Task::class)->find($taskId) : new Task();
        if ($taskId && !$task) {
            throw $this->createNotFoundException('On a perdu la tâche quelque part...');
        }

        // On prépare le formulaire et on regarde si on a soumis des données.
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        // Si le formulaire est ok, on enregistre la tâche.
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$taskId) {
                $task->setPomodoroSession($pomodoroSession);
                $task->setUser($user);
                $task->setCreatedAtValue();
            }
            $task->setUpdatedAtValue();
            $entityManager->persist($task);
            $entityManager->flush();

            // Petit message pour l'utilisateur et on le renvoie voir sa session.
            $this->addFlash('success', $taskId ? 'Tâche mise à jour, chef !' : 'Nouvelle tâche ajoutée, youpi !');
            return $this->redirectToRoute('pomodoro_session', ['id' => $pomodoroId]);
        }

        // On affiche le formulaire si on n'a pas encore soumis.
        return $this->render('task/add_to_pomodoro.html.twig', [
            'form' => $form->createView(),
            'currentPomodoroSessionId' => $pomodoroId,
        ]);
    }

    #[Route('/task/list', name: 'task_list')]
    public function list(TaskRepository $taskRepository, PomodoroSessionRepository $pomodoroSessionRepository): Response
    {
        // On récupère toutes les tâches et la session Pomodoro actuelle.
        $tasks = $taskRepository->findAll();
        $currentPomodoroSession = $pomodoroSessionRepository->findCurrentSessionForUser($this->getUser());
        $currentPomodoroSessionId = $currentPomodoroSession ? $currentPomodoroSession->getId() : null;

        // On envoie tout ça à la vue.
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'currentPomodoroSessionId' => $currentPomodoroSessionId,
        ]);
    }

    #[Route('/task/{id}', name: 'task_edit_create', requirements: ['id' => '\d+'], defaults: ['id' => null])]
    public function editOrCreate(Request $request, EntityManagerInterface $entityManager, Task $task = null): Response
    {
        // On vérifie si la tâche existe et si l'utilisateur a le droit de la tripoter.
        if ($task && $task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Eh, tu touches à quoi là ?');
        }

        // Nouvelle tâche ou édition, on prépare le formulaire.
        if (!$task) {
            $task = new Task();
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        // Si le formulaire est bon, on sauvegarde.
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $task->setCreatedAtValue();
            $task->setUpdatedAtValue();

            if (!$task->getId()) {
                $entityManager->persist($task);
            }

            $entityManager->flush();
            $this->addFlash('success', 'Tâche sauvée !');

            return $this->redirectToRoute('task_list');
        }

        // On affiche le formulaire si on a rien soumis.
        return $this->render('task/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/task/delete/{id}', name: 'task_delete')]
    public function delete(Task $task, EntityManagerInterface $entityManager): Response
    {
        // On vérifie que l'utilisateur peut supprimer la tâche.
        if ($task->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Non non non, pas touche !');
        }

        // On supprime la tâche et on dit au revoir.
        $entityManager->remove($task);
        $entityManager->flush();

        $this->addFlash('success', 'Tâche effacée, comme par magie.');
        return $this->redirectToRoute('task_list');
    }
}
