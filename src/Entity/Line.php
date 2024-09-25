<?php

namespace App\Entity;

use App\Repository\LineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LineRepository::class)]
class Line
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_time_departure = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_time_arrival = null;

    /**
     * @var Collection<int, Train>
     */
    #[ORM\OneToMany(targetEntity: Train::class, mappedBy: 'line')]
    private Collection $train;

    #[ORM\ManyToOne(inversedBy: 'line')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Stop $stop = null;

    public function __construct()
    {
        $this->train = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTimeDeparture(): ?\DateTimeInterface
    {
        return $this->date_time_departure;
    }

    public function setDateTimeDeparture(\DateTimeInterface $date_time_departure): static
    {
        $this->date_time_departure = $date_time_departure;

        return $this;
    }

    public function getDateTimeArrival(): ?\DateTimeInterface
    {
        return $this->date_time_arrival;
    }

    public function setDateTimeArrival(\DateTimeInterface $date_time_arrival): static
    {
        $this->date_time_arrival = $date_time_arrival;

        return $this;
    }

    /**
     * @return Collection<int, Train>
     */
    public function getTrain(): Collection
    {
        return $this->train;
    }

    public function addTrain(Train $train): static
    {
        if (!$this->train->contains($train)) {
            $this->train->add($train);
            $train->setLine($this);
        }

        return $this;
    }

    public function removeTrain(Train $train): static
    {
        if ($this->train->removeElement($train)) {
            // set the owning side to null (unless already changed)
            if ($train->getLine() === $this) {
                $train->setLine(null);
            }
        }

        return $this;
    }

    public function getStop(): ?Stop
    {
        return $this->stop;
    }

    public function setStop(?Stop $stop): static
    {
        $this->stop = $stop;

        return $this;
    }
}
