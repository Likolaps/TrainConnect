<?php

namespace App\Entity;

use App\Repository\StopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StopRepository::class)]
class Stop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Line>
     */
    #[ORM\OneToMany(targetEntity: Line::class, mappedBy: 'stop')]
    private Collection $line;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $station = null;

    public function __construct()
    {
        $this->line = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Line>
     */
    public function getLine(): Collection
    {
        return $this->line;
    }

    public function addLine(Line $line): static
    {
        if (!$this->line->contains($line)) {
            $this->line->add($line);
            $line->setStop($this);
        }

        return $this;
    }

    public function removeLine(Line $line): static
    {
        if ($this->line->removeElement($line)) {
            // set the owning side to null (unless already changed)
            if ($line->getStop() === $this) {
                $line->setStop(null);
            }
        }

        return $this;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): static
    {
        $this->station = $station;

        return $this;
    }
}
