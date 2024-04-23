<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToOne(inversedBy: 'profil', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $artiste = null;

    #[ORM\OneToMany(targetEntity: media::class, mappedBy: 'profil')]
    private Collection $media_profil;

    public function __construct()
    {
        $this->media_profil = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getArtiste(): ?User
    {
        return $this->artiste;
    }

    public function setArtiste(User $artiste): static
    {
        $this->artiste = $artiste;

        return $this;
    }

    /**
     * @return Collection<int, media>
     */
    public function getMediaProfil(): Collection
    {
        return $this->media_profil;
    }

    public function addMediaProfil(media $mediaProfil): static
    {
        if (!$this->media_profil->contains($mediaProfil)) {
            $this->media_profil->add($mediaProfil);
            $mediaProfil->setProfil($this);
        }

        return $this;
    }

    public function removeMediaProfil(media $mediaProfil): static
    {
        if ($this->media_profil->removeElement($mediaProfil)) {
            // set the owning side to null (unless already changed)
            if ($mediaProfil->getProfil() === $this) {
                $mediaProfil->setProfil(null);
            }
        }

        return $this;
    }
}
