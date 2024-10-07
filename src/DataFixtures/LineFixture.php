<?php

namespace App\DataFixtures;

use App\Entity\Line;
use App\Entity\Station;
use App\Entity\Stop;
use App\Entity\Train;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use PHPUnit\Framework\Constraint\Count;

class LineFixture extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $trainType = ['TGV', 'TER'];
        $trains = array();
        $stations = array();
        $lines = array();
        $stops = array();

        for ($i = 0; $i < 40; $i++) {
            $train = new Train();
            $train->setNumber(rand(10000, 99999));
            $train->setType($trainType[rand(0, 1)]);

            $manager->persist($train);
            $trains[] = $train;
        }

        for ($i = 0; $i < 300; $i++) {
            $station = new Station();
            $station->setName($faker->city);
            $station->setCity($faker->city);

            $manager->persist($station);
            $stations[] = $station;
        }

        for ($i = 0; $i < 60; $i++) {
            $line = new Line();
            $line->setDelay(rand(0, 40));
            $line->setTrain($trains[rand(0, count($trains) - 1)]);

            $manager->persist($line);
            $lines[] = $line;
        }

        for ($i = 0; $i < 200; $i++) {
            $line = $lines[rand(0, count($lines) - 1)];
            $stop = new Stop();
            $stop->setStation($stations[rand(0, count($stations) - 1)]);
            $stop->setLine($line);
            if ($line->getStops()->isEmpty()) {
                $stop->setDateTimeDeparture($faker->dateTimeBetween('-3 hours', 'now'));
            } else {
                $datetime = $line->getStops()->last()->getDateTimeArrival();
                $stop->setDateTimeDeparture($faker->dateTimeBetween($datetime, '+3 hours'));
            }
            $stop->setDateTimeArrival($faker->dateTimeBetween($stop->getDateTimeDeparture(), '+3 hours'));
            $line->addStop($stop);

            $manager->persist($stop);
            $stops[] = $stop;
        }

        // creating departure and arrival stops
        for ($i = 0; $i < count($lines); $i++) {

            // arrival

            $tempStops = $lines[$i]->getStopsInOrder();

            //var_dump($tempStops);

            $stop = new Stop();
            $lineStations = $lines[$i]->getAllStations();

            $tempStation = $stations[rand(0, count($stations) - 1)];
            do {
                $tempStation = $stations[rand(0, count($stations) - 1)];
            } while (in_array($tempStation, $lineStations));

            $stop->setStation($tempStation);
            $stop->setLine($lines[$i]);
            $datetime = $tempStops[Count($tempStops) - 1]->getDateTimeArrival();
            $stop->setDateTimeArrival($faker->dateTimeBetween($datetime, '+4 hours'));

            $manager->persist($stop);
            $stops[] = $stop;


            // departure

            $tempStops = $lines[$i]->getStopsInOrder();

            $lineStations = $lines[$i]->getAllStations();

            $tempStation = $stations[rand(0, count($stations) - 1)];
            do {
                $tempStation = $stations[rand(0, count($stations) - 1)];
            } while (in_array($tempStation, $lineStations));

            $stop = new Stop();
            $stop->setStation($tempStation);
            $stop->setLine($lines[$i]);
            $datetime = $tempStops[0]->getDateTimeDeparture();
            $stop->setDateTimeDeparture($faker->dateTimeBetween('-4 hours', $datetime));

            $manager->persist($stop);
            $stops[] = $stop;
        }



        $manager->flush();
    }
}
