<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filelink = null;

    #[ORM\ManyToOne(inversedBy: 'files', cascade:persist)]
    #[ORM\JoinColumn(nullable: false)]
    private ?ServicedObject $serviced_object = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilelink(): ?string
    {
        return $this->filelink;
    }

    public function setFilelink(string $filelink): self
    {
        $this->filelink = $filelink;

        return $this;
    }

    public function getServicedObject(): ?ServicedObject
    {
        return $this->serviced_object;
    }

    public function setServicedObject(?ServicedObject $serviced_object): self
    {
        $this->serviced_object = $serviced_object;

        return $this;
    }
}
