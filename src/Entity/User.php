<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message: 'Le prénom est obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le prénom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le prénom doit contenir au maximum {{ limit }} caractères'
    )]
    private ?string $firstname = null;

    #[Assert\NotBlank(message: 'Le nom est obligatoire')]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'Le nom doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom doit contenir au maximum {{ limit }} caractères'
    )]
    #[ORM\Column(length: 30)]
    private ?string $lastname = null;

    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    #[ORM\Column(length: 100, unique: true)]
    private ?string $email = null;

    #[Assert\Regex(
        pattern: '/^0[1-9]([-. ]?[0-9]{2}){4}$/',
        message: "Le numéro de téléphone n'est pas valide"
    )]
    #[ORM\Column(nullable: true)]
    private ?string $phone = null;

    #[Assert\NotBlank(message: 'Veuillez confirmer votre mot de passe')]
    #[Assert\EqualTo(
        propertyPath: 'password',
        message: 'Les mots de passe ne correspondent pas'
    )]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Image $image = null;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $notes;

    /**
     * @var Collection<int, Itinerary>
     */
    #[ORM\ManyToMany(targetEntity: Itinerary::class)]
    private Collection $favoriteItinerary;


    /**
     * @var Collection<int, Itinerary>
     */
    #[ORM\OneToMany(targetEntity: Itinerary::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $itineraries;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLogin = null;

    /**
     * @var Collection<int, Place>
     */
    #[ORM\ManyToMany(targetEntity: Place::class, mappedBy: 'users')]
    private Collection $favoritePlace;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->favoriteItinerary = new ArrayCollection();
        $this->favoritePlace = new ArrayCollection();
        $this->itineraries = new ArrayCollection();
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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
            $note->setUser($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getUser() === $this) {
                $note->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Itinerary>
     */
    public function getFavoriteItinerary(): Collection
    {
        return $this->favoriteItinerary;
    }

    public function addFavoriteItinerary(Itinerary $favoriteItinerary): static
    {
        if (!$this->favoriteItinerary->contains($favoriteItinerary)) {
            $this->favoriteItinerary->add($favoriteItinerary);
        }

        return $this;
    }

    public function removeFavoriteItinerary(Itinerary $favoriteItinerary): static
    {
        $this->favoriteItinerary->removeElement($favoriteItinerary);

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getFavoritePlace(): Collection
    {
        return $this->favoritePlace;
    }

    public function addFavoritePlace(Place $favoritePlace): static
    {
        if (!$this->favoritePlace->contains($favoritePlace)) {
            $this->favoritePlace->add($favoritePlace);
        }

        return $this;
    }

    public function removeFavoritePlace(Place $favoritePlace): static
    {
        $this->favoritePlace->removeElement($favoritePlace);

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection<int, Itinerary>
     */
    public function getItineraries(): Collection
    {
        return $this->itineraries;
    }

    public function addItinerary(Itinerary $itinerary): static
    {
        if (!$this->itineraries->contains($itinerary)) {
            $this->itineraries->add($itinerary);
            $itinerary->setUser($this);
        }

        return $this;
    }

    public function removeItinerary(Itinerary $itinerary): static
    {
        if ($this->itineraries->removeElement($itinerary)) {
            // set the owning side to null (unless already changed)
            if ($itinerary->getUser() === $this) {
                $itinerary->setUser(null);
            }
        }

        return $this;
    }

    public function getLastLogin(): ?\DateTimeInterface
    {
        return $this->lastLogin;
    }

    public function setLastLogin(?\DateTimeInterface $lastLogin): static
    {
        $this->lastLogin = $lastLogin;

        return $this;
    }
}
