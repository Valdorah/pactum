<?php

namespace App\Entity;

use App\Repository\DealRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DealRepository::class)
 */
class Deal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="deal_id")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, name="deal_title")
     */
    private $title;

    /**
     * @ORM\Column(type="text", name="deal_description")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="deal_url")
     */
    private $url;

    /**
     * @ORM\Column(type="float", nullable=true, name="deal_price")
     */
    private $price;

    /**
     * @ORM\Column(type="float", nullable=true, name="deal_normal_price")
     */
    private $normalPrice;

    /**
     * @ORM\Column(type="float", nullable=true, name="deal_delivery_cost")
     */
    private $deliveryCost;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="deal_discount_code")
     */
    private $discountCode;

    /**
     * @ORM\ManyToMany(targetEntity=Group::class, inversedBy="deals")
     * @ORM\JoinTable(name="deal_group",
     *   joinColumns={@ORM\JoinColumn(name="deal_id", referencedColumnName="deal_id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="group_id")}
     * )
     */
    private $groups;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="deal")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Rate::class, mappedBy="deal")
     */
    private $marks;

    /**
     * @ORM\ManyToOne(targetEntity=DealType::class, inversedBy="deals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, name="deal_image")
     */
    private $image;

    /**
     * @ORM\Column(type="datetime", name="deal_posted_at")
     */
    private $posted_at;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="savedDeal")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="postedDeals")
     * @ORM\JoinColumn(name="deal_user_id", referencedColumnName="user_id", nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $expired;

    public function __construct()
    {
        $this->groups = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->marks = new ArrayCollection();
        $this->posted_at = new \DateTime();
        $this->users = new ArrayCollection();
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

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNormalPrice(): ?float
    {
        return $this->normalPrice;
    }

    public function setNormalPrice(?float $normalPrice): self
    {
        $this->normalPrice = $normalPrice;

        return $this;
    }

    public function getDeliveryCost(): ?float
    {
        return $this->deliveryCost;
    }

    public function setDeliveryCost(?float $deliveryCost): self
    {
        $this->deliveryCost = $deliveryCost;

        return $this;
    }

    public function getDiscountCode(): ?string
    {
        return $this->discountCode;
    }

    public function setDiscountCode(?string $discountCode): self
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }

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
            $comment->setDeal($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getDeal() === $this) {
                $comment->setDeal(null);
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
            $mark->setDeal($this);
        }

        return $this;
    }

    public function removeMark(Rate $mark): self
    {
        if ($this->marks->contains($mark)) {
            $this->marks->removeElement($mark);
            // set the owning side to null (unless already changed)
            if ($mark->getDeal() === $this) {
                $mark->setDeal(null);
            }
        }

        return $this;
    }

    public function getType(): ?DealType
    {
        return $this->type;
    }

    public function setType(?DealType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeInterface
    {
        return $this->posted_at;
    }

    public function setPostedAt(?\DateTimeInterface $posted_at): self
    {
        $this->posted_at = $posted_at;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSavedDeal($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            $user->removeSavedDeal($this);
        }

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

    public function getExpired(): ?bool
    {
        return $this->expired;
    }

    public function setExpired(bool $expired): self
    {
        $this->expired = $expired;

        return $this;
    }
}
