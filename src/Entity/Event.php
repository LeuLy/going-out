<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="time")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateInscriptionEnd;

    /**
     * @ORM\Column(type="integer")
     */
    private $maxMembers;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getDateStart(): ?string
    {
        return $this->dateStart;
    }

    public function setDateStart(string $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateInscriptionEnd(): ?\DateTimeInterface
    {
        return $this->dateInscriptionEnd;
    }

    public function setDateInscriptionEnd(\DateTimeInterface $dateInscriptionEnd): self
    {
        $this->dateInscriptionEnd = $dateInscriptionEnd;

        return $this;
    }

    public function getMaxMembers(): ?string
    {
        return $this->maxMembers;
    }

    public function setMaxMembers(string $maxMembers): self
    {
        $this->maxMembers = $maxMembers;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
