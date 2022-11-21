<?php

namespace App\Entity;

use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BadgeRepository::class)
 */
class Badge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="badge_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="badge_title")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true, name="badge_description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, name="badge_image")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="badges")
     * @ORM\JoinTable(name="user_badge",
     *   joinColumns={@ORM\JoinColumn(name="badge_id", referencedColumnName="badge_id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")}
     * )
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
        }

        return $this;
    }
}
