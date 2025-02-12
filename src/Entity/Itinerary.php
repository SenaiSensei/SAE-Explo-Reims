<?php

namespace App\Entity;

use App\Repository\ItineraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItineraryRepository::class)]
class Itinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $time = null;

    #[ORM\Column(nullable: true)]
    private ?float $distance = null;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    /**
     * @var Collection<int, PathOrder>
     */
    #[ORM\OneToMany(targetEntity: PathOrder::class, mappedBy: 'itinerary', orphanRemoval: true)]
    private Collection $pathOrder;

    #[ORM\ManyToOne(inversedBy: 'itineraries')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
        $this->pathOrder = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTime(): ?float
    {
        return $this->time;
    }

    public function setTime(?float $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }

    public function setDistance(?float $distance): static
    {
        $this->distance = $distance;

        return $this;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(?float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, pathOrder>
     */
    public function getPathOrder(): Collection
    {
        return $this->pathOrder;
    }

    public function addPathOrder(PathOrder $pathOrder): static
    {
        if (!$this->pathOrder->contains($pathOrder)) {
            $this->pathOrder->add($pathOrder);
            $pathOrder->setItinerary($this);
        }

        return $this;
    }

    public function removePathOrder(PathOrder $pathOrder): static
    {
        if ($this->pathOrder->removeElement($pathOrder)) {
            // set the owning side to null (unless already changed)
            if ($pathOrder->getItinerary() === $this) {
                $pathOrder->setItinerary(null);
            }
        }

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
}
