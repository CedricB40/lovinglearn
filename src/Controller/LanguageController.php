<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LanguageController extends AbstractController
{
    #[Route(path: '/langue/{locale}', name: 'app_language_switch')]
    public function switch(string $locale, Request $request): Response
    {
        // Vérifie que la locale est valide
        if (!in_array($locale, ['fr', 'nl'])) {
            $locale = 'fr';
        }

        // Stocke la locale en session
        $request->getSession()->set('_locale', $locale);

        // Redirige vers la page précédente
        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_home');
    }
}