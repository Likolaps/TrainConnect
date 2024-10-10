<?php

namespace App\Twig\Components;

use App\Entity\Line;
use App\Repository\LineRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\HttpFoundation\Request;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent()]
class LineTable
{
    use DefaultActionTrait;
    public array $lines = [];


    public function __construct(private readonly LineRepository $lineRepository) {
    }


    public function getLines()
    {
        $departureName="";
        $arrivalName="";
        if(isset($_GET['Departure']) && isset($_GET['Arrival'])){
            $departureName = $_GET['Departure'];
            $arrivalName = $_GET['Arrival']; 
        }
        

        $query=$this->lineRepository->createQueryBuilder('line');
        if($departureName){
            $query->innerJoin('line.stops','dstops',Join::WITH,"dstops.date_time_departure IS NOT NULL")->innerJoin('dstops.station','dstation',Join::WITH,'dstation.name = :Departure')
            ->setParameter("Departure", $departureName);  
        }
        if($arrivalName){
            $query->innerJoin('line.stops','astops',Join::WITH,"astops.date_time_arrival IS NOT NULL")->innerJoin('astops.station','astation',Join::WITH,'astation.name = :Arrival')
            ->setParameter("Arrival", $arrivalName);  
        }

        $LinesArray=$query->getQuery()
        ->setMaxResults(100)->execute();
        
        return $LinesArray;
    }
}
