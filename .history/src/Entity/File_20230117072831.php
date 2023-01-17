<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]   
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $filelink = null;

    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: false)]

   
    private ?ServicedObject $serviced_object = null;
    
    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('filelink', new NotBlank());
        $metadata->addPropertyConstraint('filelink', new Type("string"));
        $metadata->addPropertyConstraint('filelink', new Assert\Length([
            'min' => 5, 
            'max' => 10, 
            'minMessage' => 'Your filelink must be at least {{ limit }} characters long',
            'maxMessage' => 'Your filelink cannot be longer than {{ limit }} characters',
        ]));
    }

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
