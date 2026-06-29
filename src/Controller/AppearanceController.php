<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppearanceController extends AbstractController
{
    #[Route(path: '/appearance/{mode}', name: 'app_appearance_switch')]
    public function switch(string $mode, Request $request): Response
    {
        if (!in_array($mode, ['dark', 'light'])) {
            $mode = 'dark';
        }

        $request->getSession()->set('_appearance', $mode);

        $referer = $request->headers->get('referer');
        if ($referer) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute('app_home');
    }
}