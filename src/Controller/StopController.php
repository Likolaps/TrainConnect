<?php

namespace App\Controller;

use App\Entity\Stop;
use App\Form\StopType;
use App\Repository\StopRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stop')]
final class StopController extends AbstractController
{
    #[Route(name: 'app_stop_index', methods: ['GET'])]
    public function index(StopRepository $stopRepository): Response
    {
        return $this->render('stop/index.html.twig', [
            'stops' => $stopRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stop_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stop = new Stop();
        $form = $this->createForm(StopType::class, $stop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stop);
            $entityManager->flush();

            return $this->redirectToRoute('app_stop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stop/new.html.twig', [
            'stop' => $stop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stop_show', methods: ['GET'])]
    public function show(Stop $stop): Response
    {
        return $this->render('stop/show.html.twig', [
            'stop' => $stop,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stop_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stop $stop, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StopType::class, $stop);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stop_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stop/edit.html.twig', [
            'stop' => $stop,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stop_delete', methods: ['POST'])]
    public function delete(Request $request, Stop $stop, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stop->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stop);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stop_index', [], Response::HTTP_SEE_OTHER);
    }
}
