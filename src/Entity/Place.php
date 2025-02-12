<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?int $PostalCode = null;

    #[ORM\Column(length: 30)]
    private ?string $city = null;

    #[ORM\Column(length: 100)]
    private ?string $address = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceMin = null;

    #[ORM\Column(nullable: true)]
    private ?float $priceMax = null;

    #[ORM\Column(nullable: true)]
    private ?float $visitTime = null;

    /**
     * @var Collection<int, TimeTable>
     */
    #[ORM\OneToMany(targetEntity: TimeTable::class, mappedBy: 'place', orphanRemoval: true)]
    private Collection $timeTable;

    /**
     * @var Collection<int, Accessibility>
     */
    #[ORM\ManyToMany(targetEntity: Accessibility::class, inversedBy: 'places')]
    private Collection $accessibility;

    #[ORM\ManyToOne(inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Category $category = null;

    /**
     * @var Collection<int, PathOrder>
     */
    #[ORM\OneToMany(targetEntity: PathOrder::class, mappedBy: 'place', cascade: ['remove'])]
    private Collection $pathOrder;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'place', cascade: ['remove'])]
    private Collection $images;

    #[ORM\Column(nullable: true)]
    private ?float $note = null;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'place', cascade: ['remove'])]
    private Collection $notes;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'favoritePlace')]
    private Collection $users;

    public function __construct()
    {
        $this->timeTable = new ArrayCollection();
        $this->accessibility = new ArrayCollection();
        $this->pathOrder = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getPostalCode(): ?int
    {
        return $this->PostalCode;
    }

    public function setPostalCode(int $PostalCode): static
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPriceMin(): ?float
    {
        return $this->priceMin;
    }

    public function setPriceMin(?float $priceMin): static
    {
        $this->priceMin = $priceMin;

        return $this;
    }

    public function getPriceMax(): ?float
    {
        return $this->priceMax;
    }

    public function setPriceMax(?float $priceMax): static
    {
        $this->priceMax = $priceMax;

        return $this;
    }

    public function getVisitTime(): ?float
    {
        return $this->visitTime;
    }

    public function setVisitTime(?float $visitTime): static
    {
        $this->visitTime = $visitTime;

        return $this;
    }

    /**
     * @return Collection<int, TimeTable>
     */
    public function getTimeTable(): Collection
    {
        return $this->timeTable;
    }

    public function addTimeTable(TimeTable $timeTable): static
    {
        if (!$this->timeTable->contains($timeTable)) {
            $this->timeTable->add($timeTable);
            $timeTable->setPlace($this);
        }

        return $this;
    }

    public function removeTimeTable(TimeTable $timeTable): static
    {
        if ($this->timeTable->removeElement($timeTable)) {
            // set the owning side to null (unless already changed)
            if ($timeTable->getPlace() === $this) {
                $timeTable->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Accessibility>
     */
    public function getAccessibility(): Collection
    {
        return $this->accessibility;
    }

    public function addAccessibility(Accessibility $accessibility): static
    {
        if (!$this->accessibility->contains($accessibility)) {
            $this->accessibility->add($accessibility);
        }

        return $this;
    }

    public function removeAccessibility(Accessibility $accessibility): static
    {
        $this->accessibility->removeElement($accessibility);

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, PathOrder>
     */
    public function getPathOrder(): Collection
    {
        return $this->pathOrder;
    }

    public function addPathOrder(PathOrder $pathOrder): static
    {
        if (!$this->pathOrder->contains($pathOrder)) {
            $this->pathOrder->add($pathOrder);
            $pathOrder->setPlace($this);
        }

        return $this;
    }

    public function removePathOrder(PathOrder $pathOrder): static
    {
        if ($this->pathOrder->removeElement($pathOrder)) {
            // set the owning side to null (unless already changed)
            if ($pathOrder->getPlace() === $this) {
                $pathOrder->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setPlace($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPlace() === $this) {
                $image->setPlace(null);
            }
        }

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

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setPlace($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getPlace() === $this) {
                $note->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }
}
