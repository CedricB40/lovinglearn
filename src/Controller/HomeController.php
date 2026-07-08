<?php

namespace App\Controller;

use App\Repository\ThemeRepository;
use App\Repository\SubjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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

    #[Route(path: '/univers/{slug}', name: 'app_theme_show_public')]
    public function showTheme(string $slug, ThemeRepository $themeRepository, SubjectRepository $subjectRepository, TranslatorInterface $translator, PaginatorInterface $paginator, Request $request): Response
    {
        $theme = $themeRepository->findOneBy(['slug' => $slug]);

        if (!$theme) {
            $this->addFlash('error', $translator->trans('flash.themeNotFound'));
            return $this->redirectToRoute('app_home');
        }

        $subjectsQuery = $subjectRepository->findBy(['theme' => $theme]);

        $pagination = $paginator->paginate(
            $subjectsQuery,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('home/theme.html.twig', [
            'theme'      => $theme,
            'pagination' => $pagination,
            'themes'     => $themeRepository->findAll(),
        ]);
    }
}