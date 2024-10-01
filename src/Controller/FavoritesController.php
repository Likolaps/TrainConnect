<?php

namespace App\Controller;

use App\Entity\Favorites;
use App\Entity\Line;
use App\Repository\FavoritesRepository;
use App\Repository\LineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FavoritesController extends AbstractController
{
    #[Route('/favorites', name: 'app_favorites')]
    public function index(FavoritesRepository $allFavs, EntityManagerInterface $entityManager): Response
    {   
        $favs=$allFavs->findAll();
        $lines=[];
        foreach($favs as $fav) 
        {
            array_push($lines , $fav->getLine());
        }

        return $this->render('favorites/index.html.twig', [
            'lines' => $lines,
        ]);
    }

    #[Route('/favorites/add/{id}', name: 'app_add_to_favorites')]
    public function addToFav(LineRepository $lines, $id, EntityManagerInterface $entityManager,FavoritesRepository $favoritesRepository): void
    {
        
        $line= $lines->find($id);
        $user=$this->getUser();
        if($favoritesRepository->findOneBy(array('line'=>$id,'user'=>$user->getId())) == [])
        {
            $fav = new Favorites();
            $fav->setLine($line);
            $fav->setUser($user);
            $entityManager->persist($fav);
            $entityManager->flush();
        }
    }
}

