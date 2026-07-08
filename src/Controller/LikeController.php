<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\User;
use App\Repository\LikeRepository;
use App\Repository\SubjectRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class LikeController extends AbstractController
{
    #[Route(path: '/subject/{id}/like', name: 'app_subject_like')]
    public function like(int $id, SubjectRepository $subjectRepository, LikeRepository $likeRepository, EntityManagerInterface $em, ThemeRepository $themeRepository, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        $subject = $subjectRepository->find($id);

        if (!$subject) {
            $this->addFlash('error', $translator->trans('flash.themeNotFound'));
            return $this->redirectToRoute('app_home');
        }

        // Vérifie si l'utilisateur a déjà liké ce sujet
        $existingLike = $likeRepository->findOneBy([
            'user'    => $user,
            'subject' => $subject,
        ]);

        if ($existingLike) {
            // Unlike — supprimer le like existant
            $em->remove($existingLike);
            $em->flush();
            $this->addFlash('success', $translator->trans('flash.unliked'));
        } else {
            // Like — créer un nouveau like
            $like = new Like();
            $like->setUser($user);
            $like->setSubject($subject);
            $em->persist($like);
            $em->flush();
            $this->addFlash('success', $translator->trans('flash.liked'));
        }

        return $this->redirectToRoute('app_subject_show', ['id' => $id]);
    }
}