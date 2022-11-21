<?php

namespace App\Entity;

use App\Repository\RateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RateRepository::class)
 */
class Rate
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="rate_id")
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="integer", name="rate_mark")
     */
    private $mark;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="marks")
     * @ORM\JoinColumn(name="rate_user", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Deal::class, inversedBy="marks")
     * @ORM\JoinColumn(name="rate_deal", referencedColumnName="deal_id", nullable=false)
     */
    private $deal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?int
    {
        return $this->mark;
    }

    public function setMark(int $mark): self
    {
        $this->mark = $mark;

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

    public function getDeal(): ?Deal
    {
        return $this->deal;
    }

    public function setDeal(?Deal $deal): self
    {
        $this->deal = $deal;

        return $this;
    }
}
