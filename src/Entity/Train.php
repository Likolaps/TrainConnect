<?php

namespace App\Entity;

use App\Repository\TrainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrainRepository::class)]
class Train
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $number = null;

    /**
     * @var Collection<int, Line>
     */
    #[ORM\OneToMany(targetEntity: Line::class, mappedBy: 'train')]
    private Collection $lines_id;

    public function __construct()
    {
        $this->lines_id = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return Collection<int, Line>
     */
    public function getLinesId(): Collection
    {
        return $this->lines_id;
    }

    public function addLinesId(Line $linesId): static
    {
        if (!$this->lines_id->contains($linesId)) {
            $this->lines_id->add($linesId);
            $linesId->setTrain($this);
        }

        return $this;
    }

    public function removeLinesId(Line $linesId): static
    {
        if ($this->lines_id->removeElement($linesId)) {
            // set the owning side to null (unless already changed)
            if ($linesId->getTrain() === $this) {
                $linesId->setTrain(null);
            }
        }

        return $this;
    }

    
}
