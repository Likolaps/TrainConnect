<?php

namespace App\Controller;

use App\Repository\LineRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
}
