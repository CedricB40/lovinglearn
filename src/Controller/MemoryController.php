<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

final class MemoryController extends AbstractController
{
    #[Route(path: '/memory', name: 'app_memory')]
    public function index(TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            if (!$user->isVerified()) {
                $this->addFlash('error', $translator->trans('flash.mustVerifyEmail'));
                return $this->redirectToRoute('app_home');
            }
        } else {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        $planets = [
            ['name' => 'Soleil', 'image' => '1-soleil.png'],
            ['name' => 'Mercure', 'image' => '2-mercure.png'],
            ['name' => 'Vénus', 'image' => '3-venus.png'],
            ['name' => 'Terre', 'image' => '4-terre.png'],
            ['name' => 'Mars', 'image' => '5-mars.png'],
            ['name' => 'Jupiter', 'image' => '6-jupiter.png'],
            ['name' => 'Saturne', 'image' => '7-saturne.png'],
            ['name' => 'Uranus', 'image' => '8-uranus.png'],
            ['name' => 'Neptune', 'image' => '9-neptune.png'],
            ['name' => 'Pluton', 'image' => '10-pluton.png'],
        ];

        return $this->render('memory/index.html.twig', [
            'planets' => $planets,
        ]);
    }
}