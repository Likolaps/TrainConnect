<?php

namespace App\DTO;

use App\Entity\Train;
use DateTime;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints\Date;

class LineDto
{
    public ?int $id = null;
    public ?int $delay = null;
    public String $trainName;
    public Collection $stops;
    public DateTime $timeDeparture;
    public DateTime $timeArrival;
    public String $departureStation;
    public String $arrivalStation;
    public String $linkToFav;
    public String $detailsBtn;
}
