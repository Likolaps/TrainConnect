<?php

namespace App\DataFixtures;

use App\Entity\Line;
use App\Entity\Station;
use App\Entity\Stop;
use App\Entity\Train;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

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
            $stop = new Stop();
            $stop->setStation($stations[rand(0, count($stations) - 1)]);
            $stop->setLine($lines[rand(0, count($lines) - 1)]);
            $stop->setDateTimeArrival($faker->dateTimeBetween('-1 day', 'now'));
            $stop->setDateTimeDeparture($faker->dateTimeBetween('now', '+1 day'));

            $manager->persist($stop);
            $stops[] = $stop;
        }

        // creating departure and arrival stops
        for ($i = 0; $i < count($lines); $i++) {
            $stop = new Stop();
            $stop->setStation($stations[rand(0, count($stations) - 1)]);
            $stop->setLine($lines[$i]);
            $stop->setDateTimeArrival($faker->dateTimeBetween('-1 day', 'now'));

            $manager->persist($stop);
            $stops[] = $stop;

            $stop = new Stop();
            $stop->setStation($stations[rand(0, count($stations) - 1)]);
            $stop->setLine($lines[$i]);
            $stop->setDateTimeDeparture($faker->dateTimeBetween('now', '+1 day'));

            $manager->persist($stop);
            $stops[] = $stop;
        }



        $manager->flush();
    }
}
