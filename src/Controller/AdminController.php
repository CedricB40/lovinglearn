<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
    #[Route(path: '/admin', name: 'app_admin')]
    public function index(TranslatorInterface $translator): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', $translator->trans('flash.accessDenied'));
            return $this->redirectToRoute('app_home');
        }

        return $this->render('admin/index.html.twig');
    }
}