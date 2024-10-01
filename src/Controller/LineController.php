<?php

namespace App\Controller;

use App\Entity\Line;
use App\Form\LineType;
use App\Repository\LineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/line')]
final class LineController extends AbstractController
{
    #[Route(name: 'app_line_index', methods: ['GET'])]
    public function index(LineRepository $lineRepository): Response
    {
        return $this->render('line/index.html.twig', [
            'lines' => $lineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_line_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $line = new Line();
        $form = $this->createForm(LineType::class, $line);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($line);
            $entityManager->flush();

            return $this->redirectToRoute('app_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('line/new.html.twig', [
            'line' => $line,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_line_show', methods: ['GET'])]
    public function show(Line $line): Response
    {
        return $this->render('line/show.html.twig', [
            'line' => $line,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_line_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Line $line, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LineType::class, $line);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_line_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('line/edit.html.twig', [
            'line' => $line,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_line_delete', methods: ['POST'])]
    public function delete(Request $request, Line $line, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $line->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($line);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_line_index', [], Response::HTTP_SEE_OTHER);
    }
}
