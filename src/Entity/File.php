<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\UploadableFilePath
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\UploadableFileName
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Gedmo\Mapping\Annotation\UploadableFileMimeType
     */
    private $mimeType;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     * @Gedmo\Mapping\Annotation\UploadableFileSize
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publicPath;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="file", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Site", mappedBy="image", cascade={"persist", "remove"})
     */
    private $site;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path)
    {
        $this->path = $path;

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

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size)
    {
        $this->size = $size;

        return $this;
    }

    public function getPublicPath(): ?string
    {
        return '/uploads/'.$this->name;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user)
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newFile = null === $user ? null : $this;
        if ($user->getFile() !== $newFile) {
            $user->setFile($newFile);
        }

        return $this;
    }

    public function setPublicPath(string $publicPath)
    {
        $this->publicPath = '/uploads/'.$this->name;;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        // set (or unset) the owning side of the relation if necessary
        $newImage = null === $site ? null : $this;
        if ($site->getImage() !== $newImage) {
            $site->setImage($newImage);
        }

        return $this;
    }

}
