<?php

namespace App\Controller;

use App\Entity\Universe;
use App\Form\UniverseType;
use App\Repository\UniverseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/universe')]
final class UniverseController extends AbstractController
{
    #[Route(name: 'app_universe_index', methods: ['GET'])]
    public function index(UniverseRepository $universeRepository): Response
    {
        return $this->render('universe/index.html.twig', [
            'universes' => $universeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_universe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $universe = new Universe();
        $form = $this->createForm(UniverseType::class, $universe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($universe);
            $entityManager->flush();

            return $this->redirectToRoute('app_universe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('universe/new.html.twig', [
            'universe' => $universe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_universe_show', methods: ['GET'])]
    public function show(Universe $universe): Response
    {
        return $this->render('universe/show.html.twig', [
            'universe' => $universe,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_universe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Universe $universe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UniverseType::class, $universe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_universe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('universe/edit.html.twig', [
            'universe' => $universe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_universe_delete', methods: ['POST'])]
    public function delete(Request $request, Universe $universe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$universe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($universe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_universe_index', [], Response::HTTP_SEE_OTHER);
    }
}
