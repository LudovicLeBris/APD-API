<?php

namespace App\Entity;

use App\Repository\RecoveryPasswordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecoveryPasswordRepository::class)]
class RecoveryPassword
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private ?string $guid = null;

    #[ORM\Column]
    private ?int $appUserId = null;

    #[ORM\Column(length: 255)]
    private ?string $appUserEmail = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $requestDateTime = null;

    #[ORM\Column]
    private ?bool $isEnable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuid(): ?string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): static
    {
        $this->guid = $guid;

        return $this;
    }

    public function getAppUserId(): ?int
    {
        return $this->appUserId;
    }

    public function setAppUserId(int $appUserId): static
    {
        $this->appUserId = $appUserId;

        return $this;
    }

    public function getAppUserEmail(): ?string
    {
        return $this->appUserEmail;
    }

    public function setAppUserEmail(string $appUserEmail): static
    {
        $this->appUserEmail = $appUserEmail;

        return $this;
    }

    public function getRequestDateTime(): ?\DateTimeImmutable
    {
        return $this->requestDateTime;
    }

    public function setRequestDateTime(\DateTimeImmutable $requestDateTime): static
    {
        $this->requestDateTime = $requestDateTime;

        return $this;
    }

    public function isIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): static
    {
        $this->isEnable = $isEnable;

        return $this;
    }
}
