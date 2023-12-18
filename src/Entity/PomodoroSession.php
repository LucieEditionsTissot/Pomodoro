<?php

namespace App\Entity;

use App\Repository\PomodoroSessionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PomodoroSessionRepository::class)]
class PomodoroSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pomodoroSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $pauseTime = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $resumeTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $workDuration = null;

    #[ORM\Column(nullable: true)]
    private ?int $breakDuration = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $completedPomodoros = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endTime = null;

    #[ORM\ManyToOne(targetEntity: Task::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Task $task = null;

    public function __construct()
    {
        $this->workDuration = 25;
        $this->breakDuration = 5;
        $this->completedPomodoros = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getWorkDuration(): ?int
    {
        if ($this->endTime === null) {
            return null;
        }

        return $this->startTime->diff($this->endTime)->i;
    }
    
    public function setWorkDuration(int $workDuration): self
    {
        $this->workDuration = $workDuration;

        return $this;
    }

    public function getBreakDuration(): ?int
    {
        return $this->breakDuration;
    }

    public function setBreakDuration(int $breakDuration): self
    {
        $this->breakDuration = $breakDuration;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCompletedPomodoros(): ?int
    {
        return $this->completedPomodoros;
    }

    public function getPauseTime(): ?\DateTimeInterface
    {
        return $this->pauseTime;
    }

    public function setPauseTime(\DateTimeInterface $pauseTime): self
    {
        $this->pauseTime = $pauseTime;

        return $this;
    }

    public function getResumeTime(): ?\DateTimeInterface
    {
        return $this->resumeTime;
    }

    public function setResumeTime(\DateTimeInterface $resumeTime): self
    {
        $this->resumeTime = $resumeTime;

        return $this;
    }


    public function setCompletedPomodoros(int $completedPomodoros): self
    {
        $this->completedPomodoros = $completedPomodoros;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;

        return $this;
    }
}
