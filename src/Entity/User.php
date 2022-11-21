<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email", "username"}, message="There is already an account with this email or username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="user_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="user_username", unique=true, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, name="user_email", unique=true, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255, name="user_password", nullable=true)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="user")
     */
    private $marks;

    /**
     * @ORM\ManyToMany(targetEntity=Deal::class, inversedBy="users")
     * @ORM\JoinTable(name="voted_deal",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="deal_id")}
     * )
     */
    private $savedDeal;

    /**
     * @ORM\OneToMany(targetEntity=Deal::class, mappedBy="user")
     */
    private $postedDeals;

    /**
     * @ORM\ManyToMany(targetEntity=Badge::class, mappedBy="user")
     */
    private $badges;

    /**
     * @ORM\OneToMany(targetEntity=Alert::class, mappedBy="user", orphanRemoval=true)
     */
    private $alerts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $avatar;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->marks = new ArrayCollection();
        $this->savedDeal = new ArrayCollection();
        $this->postedDeals = new ArrayCollection();
        $this->badges = new ArrayCollection();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return (string) $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUser($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rate[]
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Rate $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setUser($this);
        }

        return $this;
    }

    public function removeMark(Rate $mark): self
    {
        if ($this->marks->contains($mark)) {
            $this->marks->removeElement($mark);
            // set the owning side to null (unless already changed)
            if ($mark->getUser() === $this) {
                $mark->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Deal[]
     */
    public function getSavedDeal(): Collection
    {
        return $this->savedDeal;
    }

    public function addSavedDeal(Deal $savedDeal): self
    {
        if (!$this->savedDeal->contains($savedDeal)) {
            $this->savedDeal[] = $savedDeal;
        }

        return $this;
    }

    public function removeSavedDeal(Deal $savedDeal): self
    {
        if ($this->savedDeal->contains($savedDeal)) {
            $this->savedDeal->removeElement($savedDeal);
        }

        return $this;
    }

    /**
     * @return Collection|Deal[]
     */
    public function getPostedDeals(): Collection
    {
        return $this->postedDeals;
    }

    public function addPostedDeal(Deal $postedDeal): self
    {
        if (!$this->postedDeals->contains($postedDeal)) {
            $this->postedDeals[] = $postedDeal;
            $postedDeal->setUser($this);
        }

        return $this;
    }

    public function removePostedDeal(Deal $postedDeal): self
    {
        if ($this->postedDeals->contains($postedDeal)) {
            $this->postedDeals->removeElement($postedDeal);
            // set the owning side to null (unless already changed)
            if ($postedDeal->getUser() === $this) {
                $postedDeal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Badge[]
     */
    public function getBadges(): Collection
    {
        return $this->badges;
    }

    public function addBadge(Badge $badge): self
    {
        if (!$this->badges->contains($badge)) {
            $this->badges[] = $badge;
            $badge->addUser($this);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): self
    {
        if ($this->badges->contains($badge)) {
            $this->badges->removeElement($badge);
            $badge->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Alert[]
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alert $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts[] = $alert;
            $alert->setUser($this);
        }

        return $this;
    }

    public function removeAlert(Alert $alert): self
    {
        if ($this->alerts->contains($alert)) {
            $this->alerts->removeElement($alert);
            // set the owning side to null (unless already changed)
            if ($alert->getUser() === $this) {
                $alert->setUser(null);
            }
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

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
}
