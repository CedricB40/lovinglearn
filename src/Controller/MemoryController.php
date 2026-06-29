<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ThemeRepository;
use App\Repository\SubjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class MemoryController extends AbstractController
{
    #[Route(path: '/memory', name: 'app_memory')]
    public function select(ThemeRepository $themeRepository, TranslatorInterface $translator): Response
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

        $themes = $themeRepository->findAll();

        return $this->render('memory/select.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route(path: '/memory/{slug}', name: 'app_memory_modes')]
    public function modes(string $slug, ThemeRepository $themeRepository, SubjectRepository $subjectRepository, TranslatorInterface $translator): Response
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

        $theme = $themeRepository->findOneBy(['slug' => $slug]);

        if (!$theme) {
            $this->addFlash('error', $translator->trans('flash.themeNotFound'));
            return $this->redirectToRoute('app_memory');
        }

        $subjects = $subjectRepository->findBy(['theme' => $theme]);

        // Vérifie si le thème a des images
        $hasImages = false;
        foreach ($subjects as $subject) {
            if ($subject->getImage()) {
                $hasImages = true;
                break;
            }
        }

        return $this->render('memory/modes.html.twig', [
            'theme'     => $theme,
            'subjects'  => $subjects,
            'hasImages' => $hasImages,
            'themes'    => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/memory/{slug}/{mode}', name: 'app_memory_play')]
    public function play(string $slug, string $mode, ThemeRepository $themeRepository, SubjectRepository $subjectRepository, TranslatorInterface $translator): Response
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

        // Vérifie que le mode est valide
        if (!in_array($mode, ['nom-image', 'image-image'])) {
            return $this->redirectToRoute('app_memory_modes', ['slug' => $slug]);
        }

        $theme = $themeRepository->findOneBy(['slug' => $slug]);

        if (!$theme) {
            $this->addFlash('error', $translator->trans('flash.themeNotFound'));
            return $this->redirectToRoute('app_memory');
        }

        $subjects = $subjectRepository->findBy(['theme' => $theme]);

        // Construit les données des cartes selon le mode
        $cards = [];
        foreach ($subjects as $subject) {
            if ($subject->getImage()) {
                $cards[] = [
                    'name'  => $subject->getName(),
                    'image' => $subject->getImage(),
                ];
            }
        }

        return $this->render('memory/index.html.twig', [
            'theme'  => $theme,
            'cards'  => $cards,
            'mode'   => $mode,
            'themes' => $themeRepository->findAll(),
        ]);
    }
}