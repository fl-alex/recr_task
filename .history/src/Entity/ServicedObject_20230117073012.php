<?php

namespace App\Entity;

use App\Repository\ServicedObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServicedObjectRepository::class)]
class ServicedObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'serviced_object', 
                    targetEntity: File::class, 
                    orphanRemoval: true, 
                    cascade:["persist"])] // make cascading saved related entities

    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('name', new Type("string"));
        $metadata->addPropertyConstraint('name', new Assert\Length([
            'min' => 3, 
            'max' => 10, 
            'minMessage' => 'Your objectname must be at least {{ limit }} characters long',
            'maxMessage' => 'Your  objectname cannot be longer than {{ limit }} characters',
        ]));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): self
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setServicedObject($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getServicedObject() === $this) {
                $file->setServicedObject(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getName(); 
    }
    
}
