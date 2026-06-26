<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class AccountController extends AbstractController
{
    #[Route(path: '/account', name: 'app_account')]
    public function index(TranslatorInterface $translator): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/index.html.twig');
    }
}