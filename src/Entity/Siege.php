<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\SiegeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiegeRepository::class)]
#[ORM\Table(name: 'siege', schema: 'cinephaliaapi')]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: "is_granted('ROLE_API_ECRITURE') or is_granted('ROLE_ADMIN')")
    ],
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_API_LECTURE')"
)]
class Siege
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $numero = null;

    #[ORM\ManyToOne(inversedBy: 'sieges')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Salle $salle = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPMR = false;

    /**
     * @var Collection<int, LinkReservationSiege>
     */
    #[ORM\OneToMany(targetEntity: LinkReservationSiege::class, mappedBy: 'siege')]
    private Collection $linkReservationSieges;

    public function __construct()
    {
        $this->linkReservationSieges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): static
    {
        $this->salle = $salle;

        return $this;
    }

    public function isPMR(): ?bool
    {
        return $this->isPMR;
    }

    public function setPMR(bool $isPMR): static
    {
        $this->isPMR = $isPMR;

        return $this;
    }

    /**
     * @return Collection<int, LinkReservationSiege>
     */
    public function getLinkReservationSieges(): Collection
    {
        return $this->linkReservationSieges;
    }

    public function addLinkReservationSiege(LinkReservationSiege $linkReservationSiege): static
    {
        if (!$this->linkReservationSieges->contains($linkReservationSiege)) {
            $this->linkReservationSieges->add($linkReservationSiege);
            $linkReservationSiege->setSiege($this);
        }

        return $this;
    }

    public function removeLinkReservationSiege(LinkReservationSiege $linkReservationSiege): static
    {
        if ($this->linkReservationSieges->removeElement($linkReservationSiege)) {
            // set the owning side to null (unless already changed)
            if ($linkReservationSiege->getSiege() === $this) {
                $linkReservationSiege->setSiege(null);
            }
        }

        return $this;
    }
}
