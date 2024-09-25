<?php

namespace App\Entity;

use App\Repository\StopRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StopRepository::class)]
class Stop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_time_departure = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_time_arrival = null;

    #[ORM\ManyToOne(inversedBy: 'stops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Line $line = null;

    #[ORM\ManyToOne(inversedBy: 'stops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Station $station = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTimeDeparture(): ?\DateTimeInterface
    {
        return $this->date_time_departure;
    }

    public function setDateTimeDeparture(?\DateTimeInterface $date_time_departure): static
    {
        $this->date_time_departure = $date_time_departure;

        return $this;
    }

    public function getDateTimeArrival(): ?\DateTimeInterface
    {
        return $this->date_time_arrival;
    }

    public function setDateTimeArrival(?\DateTimeInterface $date_time_arrival): static
    {
        $this->date_time_arrival = $date_time_arrival;

        return $this;
    }

    public function getLine(): ?Line
    {
        return $this->line;
    }

    public function setLine(?Line $line): static
    {
        $this->line = $line;

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
