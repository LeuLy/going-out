<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @Assert\GreaterThan("now Europe/Paris")
     */
    private $dateStart;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual("30",
     *      message = "La durée minimale doit être de 30 minutes")
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("now Europe/Paris")
     */
    private $dateInscriptionEnd;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual("2")
     */
    private $maxMembers;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="events", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $place;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="events")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inscription", mappedBy="event", cascade={"persist", "remove"})
     */
    private $inscriptions;

    public function __construct()
    {
//        $this->members = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getDateStart()
    {
        return $this->dateStart;
    }

    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    public function getDateInscriptionEnd()
    {
        return $this->dateInscriptionEnd;
    }

    public function setDateInscriptionEnd($dateInscriptionEnd)
    {
        $this->dateInscriptionEnd = $dateInscriptionEnd;
    }

    public function getMaxMembers()
    {
        return $this->maxMembers;
    }

    public function setMaxMembers($maxMembers)
    {
        $this->maxMembers = $maxMembers;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator($creator)
    {
        $this->creator = $creator;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site)
    {
        $this->site = $site;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place)
    {
        $this->place = $place;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription): self
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setEvent($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription): self
    {
        if ($this->inscriptions->contains($inscription)) {
            $this->inscriptions->removeElement($inscription);
            // set the owning side to null (unless already changed)
            if ($inscription->getEvent() === $this) {
                $inscription->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload){
        // check if the $dateInscriptionEnd is after the $dateStart
        if ($this->getDateInscriptionEnd() > $this->getDateStart()) {
            $context->buildViolation('La date de fin d\'inscription doit correpondre au jour de début au plus tard')
                    ->atPath('dateInscriptionEnd')
                    ->addViolation();
        }
    }


// Attr not needed
//    /**
//     * @ORM\Column(type="integer", nullable=true)
//     */
//    private $nbMember;
//
//    public function getNbMember(): ?int
//    {
//        return $this->nbMember;
//    }
//
//    public function setNbMember(?int $nbMember): self
//    {
//        $this->nbMember = $nbMember;
//
//        return $this;
//    }


// Relation ManyToMany replaced by Inscription
//    /**
//     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="eventsMember")
//     */
//    private $members;
//
//
//    /**
//     * @return Collection|User[]
//     */
//    public function getMembers(): Collection
//    {
//        return $this->members;
//    }
//
//    public function addMember(User $member)
//    {
//        if (!$this->members->contains($member)) {
//            $this->members[] = $member;
//        }
//
//        return $this;
//    }
//
//    public function removeMember(User $member)
//    {
//        if ($this->members->contains($member)) {
//            $this->members->removeElement($member);
//        }
//
//        return $this;
//    }
}
