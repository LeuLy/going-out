<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
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
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^[A-Za-zÀ-ÖØ-öø-ÿç-]{1,245}$/i",
     *     htmlPattern="^[A-Za-zÀ-ÖØ-öø-ÿç-]{1,245}$",
     *     match=true,
     *     message="Le nom ne peut contenir que des lettres")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Regex(
     *     pattern="/^[A-Za-zÀ-ÖØ-öø-ÿç-]{1,245}$/i",
     *     htmlPattern="^[A-Za-zÀ-ÖØ-öø-ÿç-]{1,245}$",
     *     match=true,
     *     message="Le prénom ne peut contenir que des lettres")
     */
    private $firstname;

    /**
     * @ORM\Column(type="integer")
     */
    private $inscriptionYear;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Assert\Regex(
     *     pattern="/^[0-9]{10}$/i",
     *     htmlPattern="^[0-9]{10}$",
     *     match=true,
     *     message="Votre numéro ne peut contenir que des chiffres")
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $password;

    /**
     * @ORM\Column(type="array")
     */
    private $roles = ['ROLE_USER'];

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="creator")
     */
    private $events;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\File", mappedBy="user", cascade={"persist", "remove"})
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $site;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Inscription", mappedBy="user", orphanRemoval=false)
     */
    private $inscriptions;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $showPhone;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $erased;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passwordToken;

    public function __construct()
    {
        $this->events = new ArrayCollection();
//        $this->eventsMember = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getInscriptionYear(): ?int
    {
        return $this->inscriptionYear;
    }

    public function setInscriptionYear(int $inscriptionYear)
    {
        $this->inscriptionYear = $inscriptionYear;

        return $this;
    }


    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        if (empty($this->roles)) {
            $this->roles = ['ROLE_USER'];
        }

        return $this->roles;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event)
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setCreator($this);
        }

        return $this;
    }

    public function removeEvent(Event $event)
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getCreator() === $this) {
                $event->setCreator(null);
            }
        }

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file)
    {
        $this->file = $file;

        return $this;
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

    /**
     * @return Collection|Inscription[]
     */
    public function getInscriptions(): Collection
    {
        return $this->inscriptions;
    }

    public function addInscription(Inscription $inscription)
    {
        if (!$this->inscriptions->contains($inscription)) {
            $this->inscriptions[] = $inscription;
            $inscription->setUser($this);
        }

        return $this;
    }

    public function removeInscription(Inscription $inscription)
    {
        if ($this->inscriptions->contains($inscription)) {
            $this->inscriptions->removeElement($inscription);
            // set the owning side to null (unless already changed)
            if ($inscription->getUser() === $this) {
                $inscription->setUser(null);
            }
        }

        return $this;
    }

    public function getShowPhone(): ?bool
    {
        return $this->showPhone;
    }

    public function setShowPhone(?bool $showPhone)
    {
        $this->showPhone = $showPhone;

        return $this;
    }

    public function getErased(): ?bool
    {
        return $this->erased;
    }

    public function setErased(?bool $erased)
    {
        $this->erased = $erased;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPasswordToken(): ?string
    {
        return $this->passwordToken;
    }

    public function setPasswordToken(?string $passwordToken)
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }


// Relation ManyToMany replaced by Inscription
//    /**
//     * @ORM\ManyToMany(targetEntity="App\Entity\Event", mappedBy="members")
//     */
//    private $eventsMember;
//
//
//    /**
//     * @return Collection|Event[]
//     */
//    public function getEventsMember(): Collection
//    {
//        return $this->eventsMember;
//    }
//
//    public function addEventsMember(Event $eventsMember)
//    {
//        if (!$this->eventsMember->contains($eventsMember)) {
//            $this->eventsMember[] = $eventsMember;
//            $eventsMember->addMember($this);
//        }
//
//        return $this;
//    }
//
//    public function removeEventsMember(Event $eventsMember)
//    {
//        if ($this->eventsMember->contains($eventsMember)) {
//            $this->eventsMember->removeElement($eventsMember);
//            $eventsMember->removeMember($this);
//        }
//
//        return $this;
//    }
}


