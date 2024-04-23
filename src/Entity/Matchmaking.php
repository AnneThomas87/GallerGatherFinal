<?php

namespace App\Entity;

use App\Repository\MatchmakingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchmakingRepository::class)]
class Matchmaking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'matchmakings')]
    private ?User $map_user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMapUser(): ?User
    {
        return $this->map_user;
    }

    public function setMapUser(?User $map_user): static
    {
        $this->map_user = $map_user;

        return $this;
    }
}
