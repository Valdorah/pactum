<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="comment_id")
     */
    private $id;

    /**
     * @ORM\Column(type="text", name="comment_text")
     */
    private $text;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     * @ORM\JoinColumn(name="comment_user", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Deal::class, inversedBy="comments")
     * @ORM\JoinColumn(name="comment_deal", referencedColumnName="deal_id", nullable=false)
     */
    private $deal;

    /**
     * @ORM\Column(type="datetime", name="comment_posted_at")
     */
    private $postedAt;

    public function __construct()
    {
        $this->postedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

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

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeInterface $postedAt): self
    {
        $this->postedAt = $postedAt;

        return $this;
    }
}
