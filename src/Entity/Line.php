<?php

namespace App\Entity;

use App\Repository\LineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Array_;

#[ORM\Entity(repositoryClass: LineRepository::class)]
class Line
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $delay = null;

    #[ORM\ManyToOne(inversedBy: 'lines_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Train $train = null;

    /**
     * @var Collection<int, Stop>
     */
    #[ORM\OneToMany(targetEntity: Stop::class, mappedBy: 'line')]
    private Collection $stops;

    public function __construct()
    {
        $this->stops = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDelay(): ?int
    {
        return $this->delay;
    }

    public function setDelay(?int $delay): static
    {
        $this->delay = $delay;

        return $this;
    }

    public function getTrain(): ?Train
    {
        return $this->train;
    }

    public function setTrain(?Train $train): static
    {
        $this->train = $train;

        return $this;
    }

    /**
     * @return Collection<int, Stop>
     */
    public function getStops(): Collection
    {
        return $this->stops;
    }

    public function addStop(Stop $stop): static
    {
        if (!$this->stops->contains($stop)) {
            $this->stops->add($stop);
            $stop->setLine($this);
        }

        return $this;
    }

    public function removeStop(Stop $stop): static
    {
        if ($this->stops->removeElement($stop)) {
            // set the owning side to null (unless already changed)
            if ($stop->getLine() === $this) {
                $stop->setLine(null);
            }
        }

        return $this;
    }

    public function getStopsInOrder(): Collection
    {
        $firstStop = null;
        $lastStop = null;

        $stopsArray = new Collection();

        foreach ($this->stops as $stop) {
            if ($stop->getDateTimeArrival() == null && $firstStop == null) {
                $firstStop = $stop;
            }
            if ($stop->getDateTimeDeparture() == null && $lastStop == null) {
                $lastStop = $stop;
            }

            // getting stops in order of their datetime arrival
            if ($stopsArray->isEmpty()) {
                $stopsArray->add($stop);
            } else {
                $i = 0;
                $added = false;
                foreach ($stopsArray as $stopArray) {
                    if ($stop->getDateTimeArrival() < $stopArray->getDateTimeArrival()) {
                        // splicing the array to add in the middle
                        $firstArray = new Collection();
                        $secondArray = new Collection();

                        foreach ($stopsArray as $key => $value) {
                            if ($key < $i) {
                                $firstArray->add($value);
                            } else {
                                $secondArray->add($value);
                            }
                        }
                        $firstArray->add($stop);

                        //adding back the second array
                        foreach ($secondArray as $value) {
                            $firstArray->add($value);
                        }

                        $stopsArray = $firstArray;
                        $added = true;
                        break;
                    }
                    $i++;
                }
                if (!$added) {
                    $stopsArray->add($stop);
                }
            }
        }

        // adding first and last stop
        $tempArray = new Collection();
        $tempArray->add($firstStop);
        foreach ($stopsArray as $stopArray) {
            $tempArray->add($stopArray);
        }
        $tempArray->add($lastStop);
        $stopsArray = $tempArray;

        return $stopsArray;
    }
}
