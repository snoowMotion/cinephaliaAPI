<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\DataPersister\FilmInputDataTransformer;
use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
#[ORM\Table(name: 'film', schema: 'cinephaliaapi')]

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']], // On désactive la désérialisation JSON pour cette opération
            security: "is_granted('ROLE_API_ECRITURE') or is_granted('ROLE_ADMIN')",
            deserialize: false,
        processor: FilmInputDataTransformer::class
        )
    ],
    normalizationContext: ['groups' => ['film:read']],
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_LECTURE')"
)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['film:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, options: ['default' => ''])]
    #[Groups(['film:read'])]
    private ?string $titre = '';

    #[ORM\Column(length: 2500, options: ['default' => ''])]
    #[Groups(['film:read'])]
    private ?string $synopsis = '';

    #[ORM\Column(length: 255, options: ['default' => ''])]
    #[Groups(['film:read'])]
    private ?string $afficheUrl = '';

    #[ORM\Column(options: ['default' => 0])]
    #[Groups(['film:read'])]
    private ?int $ageMini = 0;

    #[ORM\Column(length: 255, options: ['default' => ''])]
    #[Groups(['film:read'])]
    private ?string $label = '';

    #[ORM\ManyToOne(inversedBy: 'films')]
    #[Groups(['film:read'])]
    private ?Genre $genre = null;

    /**
     * @var Collection<int, Seance>
     */
    #[ORM\OneToMany(targetEntity: Seance::class, mappedBy: 'film')]
    private Collection $seances;

    public function __construct()
    {
        $this->seances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getAfficheUrl(): ?string
    {
        return $this->afficheUrl;
    }

    public function setAfficheUrl(string $afficheUrl): static
    {
        $this->afficheUrl = $afficheUrl;

        return $this;
    }

    public function getAgeMini(): ?int
    {
        return $this->ageMini;
    }

    public function setAgeMini(int $ageMini): static
    {
        $this->ageMini = $ageMini;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection<int, Seance>
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): static
    {
        if (!$this->seances->contains($seance)) {
            $this->seances->add($seance);
            $seance->setFilm($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): static
    {
        if ($this->seances->removeElement($seance)) {
            // set the owning side to null (unless already changed)
            if ($seance->getFilm() === $this) {
                $seance->setFilm(null);
            }
        }

        return $this;
    }
}
