<?php

namespace App\Controller;

use App\DTO\LineDto;
use App\Entity\Station;
use App\Repository\LineRepository;
use App\Repository\StationRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(LineRepository $lineRepository): Response
    {
        $lines = $lineRepository->findAll();

        if (isset($_POST)&& $_POST != []) {
            dd($_POST);
        }


        return $this->render('default/index.html.twig', [
            'lines' => $lines,
        ]);
    }

    #[Route('/ajax/get/', name: 'app_ajax_get', methods:['GET'])]
    public function testAjax(Request $request,LineRepository $lineRepo,UrlGeneratorInterface $url): Response
    {   
        
        $departureName = $request->query->get("Departure");
        $arrivalName = $request->query->get("Arrival");

        $query=$lineRepo->createQueryBuilder('line');
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
        $lineDtoArray=[];
        foreach($LinesArray as $line){
            $lineDto = new LineDto();
            $lineDto->id= $line->getId();
            $lineDto->delay= $line->getDelay();
            $lineDto->trainName= $line->getTrain()->getType();
            $lineDto->stops= $line->getStops();
            $lineDto->timeDeparture= $line->getStopsInOrder()[0]->getDateTimeDeparture();
            $lineDto->timeArrival= end( $line->getStopsInOrder())->getDateTimeArrival();
            $lineDto->departureStation= $line->getStopsInOrder()[0]->getStation()->getName();
            $lineDto->arrivalStation=end( $line->getStopsInOrder())->getStation()->getName();

            
            $lineDto->linkToFav = "<td><a href='{{ path('app_add_to_favorites', { id: $lineDto->id }) }}'
             class='btn btn-warning add-to-fav'>Add to fav</a></td>";
            $lineDto->detailsBtn = "<td data-bs-toggle='collapse' data-bs-target='{{ '#accordion' ~ $lineDto->id }}' class='clickable'>
				<input type='image' src={{asset('img/details.png')}} width='30' height='30' class=''>
			</td>";
            $lineDtoArray[]=$lineDto;

        }

        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function (object $object, ?string $format, array $context): string {
                return "";
            },
        ];

        //return $this->json($lineDtoArray,context: $defaultContext);
        return $this->json($lineDtoArray,context: $defaultContext);
        
    }


}
