<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $generalAltitude = null;

    #[ORM\Column]
    private ?float $generalTemperature = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'project', targetEntity: DuctNetwork::class, orphanRemoval: true, cascade: ['PERSIST'])]
    private Collection $ductNetworks;

    public function __construct()
    {
        $this->ductNetworks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGeneralAltitude(): ?int
    {
        return $this->generalAltitude;
    }

    public function setGeneralAltitude(int $generalAltitude): static
    {
        $this->generalAltitude = $generalAltitude;

        return $this;
    }

    public function getGeneralTemperature(): ?float
    {
        return $this->generalTemperature;
    }

    public function setGeneralTemperature(float $temperature): static
    {
        $this->generalTemperature = $temperature;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, DuctNetwork>
     */
    public function getDuctNetworks(): Collection
    {
        return $this->ductNetworks;
    }

    public function addDuctNetwork(DuctNetwork $ductNetwork): static
    {
        if (!$this->ductNetworks->contains($ductNetwork)) {
            $this->ductNetworks->add($ductNetwork);
            $ductNetwork->setProject($this);
        }

        return $this;
    }

    public function removeDuctNetwork(DuctNetwork $ductNetwork): static
    {
        if ($this->ductNetworks->removeElement($ductNetwork)) {
            // set the owning side to null (unless already changed)
            if ($ductNetwork->getProject() === $this) {
                $ductNetwork->setProject(null);
            }
        }

        return $this;
    }
}
