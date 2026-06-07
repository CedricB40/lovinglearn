<?php

namespace App\Controller;

use App\Repository\ThemeRepository;
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
}