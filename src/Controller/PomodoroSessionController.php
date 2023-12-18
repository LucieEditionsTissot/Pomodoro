<?php

namespace App\Controller;

use App\Entity\PomodoroSession;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class PomodoroSessionController extends AbstractController
{
    // Lancement d'une nouvelle session Pomodoro
    #[Route('/pomodoro/start', name: 'pomodoro_start')]
    public function start(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $pomodoroSession = (new PomodoroSession())
            ->setUser($user)
            ->setStartTime(new \DateTime())
            ->setStatus('active')
            ->setWorkDuration(25)
            ->setBreakDuration(5);

        $entityManager->persist($pomodoroSession);
        $entityManager->flush();

        return $this->render('pomodoro_session/index.html.twig', [
            'pomodoroSession' => $pomodoroSession
        ]);
    }

    // Mise en pause d'une session Pomodoro
    #[Route('/pomodoro/pause/{id}', name: 'pomodoro_pause')]
    public function pause(EntityManagerInterface $entityManager, PomodoroSession $pomodoroSession): Response
    {
        if ($pomodoroSession->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $pomodoroSession->setPauseTime(new \DateTime())
            ->setStatus('paused');

        $entityManager->flush();

        return $this->render('pomodoro_session/index.html.twig', [
            'pomodoroSession' => $pomodoroSession
        ]);
    }

    // Reprise d'une session Pomodoro
    #[Route('/pomodoro/resume/{id}', name: 'pomodoro_resume')]
    public function resume(EntityManagerInterface $entityManager, PomodoroSession $pomodoroSession): Response
    {
        if ($pomodoroSession->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $pomodoroSession->setResumeTime(new \DateTime())
            ->setStatus('active');

        $entityManager->flush();

        return $this->render('pomodoro_session/index.html.twig', [
            'pomodoroSession' => $pomodoroSession
        ]);
    }

    // Affichage d'une session Pomodoro
    #[Route('/pomodoro/session/{id}', name: 'pomodoro_session')]
    public function show(EntityManagerInterface $entityManager, int $id): Response
    {
        $pomodoroSession = $entityManager->getRepository(PomodoroSession::class)->find($id);

        if (!$pomodoroSession) {
            throw $this->createNotFoundException('Session Pomodoro non trouvée.');
        }

        return $this->render('pomodoro_session/index.html.twig', [
            'pomodoroSession' => $pomodoroSession
        ]);
    }

    // Fin d'une session Pomodoro
    #[Route('/pomodoro/end/{id}', name: 'pomodoro_end')]
    public function end(EntityManagerInterface $entityManager, PomodoroSession $pomodoroSession): Response
    {
        if ($pomodoroSession->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $pomodoroSession->setEndTime(new \DateTime())
            ->setStatus('completed');

        $entityManager->flush();

        return $this->redirectToRoute('pomodoro_home');
    }

    // Liste des sessions Pomodoro terminées
    #[Route('/pomodoro/completed-sessions', name: 'pomodoro_completed_sessions')]
    public function listCompletedSessions(EntityManagerInterface $entityManager): Response
    {
        $completedSessions = $entityManager->getRepository(PomodoroSession::class)
            ->findBy(['status' => 'completed']);

        return $this->render('pomodoro_session/completed.html.twig', [
            'completedSessions' => $completedSessions,
        ]);
    }

    #[Route('/pomodoro/session/{id}/tasks', name: 'pomodoro_session_tasks')]
    public function manageTasks(EntityManagerInterface $entityManager, int $id): Response
    {
        $pomodoroSession = $entityManager->getRepository(PomodoroSession::class)->find($id);

        // Vérification de l'existence de la session et des droits de l'utilisateur
        if (!$pomodoroSession) {
            throw $this->createNotFoundException('Session Pomodoro non trouvée.');
        }

        if ($pomodoroSession->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Récupération des tâches associées à la session Pomodoro
        $tasks = $entityManager->getRepository(Task::class)->findBy(['pomodoroSession' => $pomodoroSession]);

        // Affichage de la vue avec les tâches
        return $this->render('task/list.html.twig', [
            'pomodoroSession' => $pomodoroSession,
            'tasks' => $tasks,
            'currentPomodoroSessionId' => $id
        ]);
    }
}


