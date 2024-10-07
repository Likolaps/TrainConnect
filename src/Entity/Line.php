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

    /**
     * @var Collection<int, Favorites>
     */
    #[ORM\OneToMany(targetEntity: Favorites::class, mappedBy: 'line')]
    private Collection $favorites;

    public function __construct()
    {
        $this->stops = new ArrayCollection();
        $this->favorites = new ArrayCollection();
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
    /**
     * @return Collection<int, Favorites>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorites $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setLine($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getLine() === $this) {
                $favorite->setLine(null);
            }
        }

        return $this;
    }

    // get all stops in order of their time of arrival
    public function getStopsInOrder(): array
    {
        $stops = $this->stops->toArray();
        usort($stops, function ($a, $b) {
            return $a->getDateTimeArrival() <=> $b->getDateTimeArrival();
        });
        return $stops;
    }

    public function getAllStations(): array
    {
        $stations = [];
        foreach ($this->stops as $stop) {
            $stations[] = $stop->getStation();
        }
        return $stations;
    }

    // public function getStopsInOrder(): Collection
    // {
    //     $firstStop = null;
    //     $lastStop = null;

    //     $stopsArray = new ArrayCollection();

    //     foreach ($this->stops as $stop) {
    //         if ($stop->getDateTimeArrival() == null && $firstStop == null) {
    //             $firstStop = $stop;
    //             continue;
    //         }
    //         if ($stop->getDateTimeDeparture() == null && $lastStop == null) {
    //             $lastStop = $stop;
    //             continue;
    //         }

    //         // getting stops in order of their datetime arrival
    //         if ($stopsArray->isEmpty()) {
    //             $stopsArray->add($stop);
    //         } else {
    //             $i = 0;
    //             $added = false;
    //             foreach ($stopsArray as $stopArray) {
    //                 if ($stop->getDateTimeArrival() < $stopArray->getDateTimeArrival()) {
    //                     // splicing the array to add in the middle
    //                     $firstArray = new ArrayCollection();
    //                     $secondArray = new ArrayCollection();

    //                     foreach ($stopsArray as $key => $value) {
    //                         if ($key < $i) {
    //                             $firstArray->add($value);
    //                         } else {
    //                             $secondArray->add($value);
    //                         }
    //                     }
    //                     $firstArray->add($stop);

    //                     //adding back the second array
    //                     foreach ($secondArray as $value) {
    //                         $firstArray->add($value);
    //                     }

    //                     $stopsArray = $firstArray;
    //                     $added = true;
    //                     break;
    //                 }
    //                 $i++;
    //             }
    //             if (!$added) {
    //                 $stopsArray->add($stop);
    //             }
    //         }
    //     }



    //     // adding first and last stop
    //     $tempArray = new ArrayCollection();
    //     $tempArray->add($firstStop);
    //     foreach ($stopsArray as $stopArray) {
    //         $tempArray->add($stopArray);
    //     }
    //     $tempArray->add($lastStop);
    //     $stopsArray = $tempArray;

    //     return $stopsArray;
    // }
}
