<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[IsGranted('ROLE_ADMIN')]
class ThemeController extends AbstractController
{
    #[Route(path: '/theme/', name: 'app_theme_index', methods: ['GET'])]
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('theme/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/theme/new', name: 'app_theme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, TranslatorInterface $translator, ThemeRepository $themeRepository): Response
    {
        $theme = new Theme();
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($theme->getName())->lower();
            $theme->setSlug($slug);
            $entityManager->persist($theme);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('flash.theme.created', ['%name%' => $theme->getName()]));
            return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('theme/new.html.twig', [
            'theme'  => $theme,
            'form'   => $form,
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/theme/{id}', name: 'app_theme_show', methods: ['GET'])]
    public function show(Theme $theme, ThemeRepository $themeRepository): Response
    {
        return $this->render('theme/show.html.twig', [
            'theme'  => $theme,
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/theme/{id}/edit', name: 'app_theme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Theme $theme, EntityManagerInterface $entityManager, SluggerInterface $slugger, TranslatorInterface $translator, ThemeRepository $themeRepository): Response
    {
        $form = $this->createForm(ThemeType::class, $theme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($theme->getName())->lower();
            $theme->setSlug($slug);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('flash.theme.updated', ['%name%' => $theme->getName()]));
            return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('theme/edit.html.twig', [
            'theme'  => $theme,
            'form'   => $form,
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/theme/{id}', name: 'app_theme_delete', methods: ['POST'])]
    public function delete(Request $request, Theme $theme, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $theme->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($theme);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('flash.theme.deleted'));
        }

        return $this->redirectToRoute('app_theme_index', [], Response::HTTP_SEE_OTHER);
    }
}