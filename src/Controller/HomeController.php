<?php

namespace App\Controller;

use App\Repository\ThemeRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'app_home')]
    public function index(ThemeRepository $repository): Response
    {
        $themes = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route(path: '/theme/{slug}', name: 'app_theme_show_public')]
    public function showTheme(string $slug, ThemeRepository $themeRepository, SubjectRepository $subjectRepository): Response
    {
        $theme = $themeRepository->findOneBy(['slug' => $slug]);

        if (!$theme) {
            $this->addFlash('error', 'Thème introuvable !');
            return $this->redirectToRoute('app_home');
        }

        $subjects = $subjectRepository->findBy(['theme' => $theme]);

        return $this->render('home/theme.html.twig', [
            'theme' => $theme,
            'subjects' => $subjects,
        ]);
    }
}