<?php

namespace App\Entity;

use App\Repository\AlertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AlertRepository::class)
 */
class Alert
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="alert_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", name="alert_keyWord")
     */
    private $keyWord;

    /**
     * @ORM\Column(type="integer", name="alert_min_temperature")
     */
    private $minTemperature;

    /**
     * @ORM\Column(type="boolean", name="alert_is_notified")
     */
    private $isNotified;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="alerts")
     * @ORM\JoinColumn(name="alert_user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getkeyWord(): ?string
    {
        return $this->keyWord;
    }

    public function setkeyWord(string $keyWord): self
    {
        $this->keyWord = $keyWord;

        return $this;
    }

    public function getMinTemperature(): ?int
    {
        return $this->minTemperature;
    }

    public function setMinTemperature(int $minTemperature): self
    {
        $this->minTemperature = $minTemperature;

        return $this;
    }

    public function getIsNotified(): ?bool
    {
        return $this->isNotified;
    }

    public function setIsNotified(bool $isNotified): self
    {
        $this->isNotified = $isNotified;

        return $this;
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
}
