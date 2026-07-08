<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class CommentController extends AbstractController
{
    #[Route(path: '/subject/{id}/comment', name: 'app_comment_add', methods: ['POST'])]
    public function add(int $id, Request $request, SubjectRepository $subjectRepository, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        $subject = $subjectRepository->find($id);

        if (!$subject) {
            return $this->redirectToRoute('app_home');
        }

        $content = $request->request->all('comment')['content'] ?? '';

        if (!empty(trim($content))) {
            $comment = new Comment();
            $comment->setUser($user);
            $comment->setSubject($subject);
            $comment->setContent($content);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $em->persist($comment);
            $em->flush();
            $this->addFlash('success', $translator->trans('flash.commentAdded'));
        }

        return $this->redirectToRoute('app_subject_show', ['id' => $id]);
    }

    #[Route(path: '/comment/{id}/delete', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(int $id, CommentRepository $commentRepository, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $comment = $commentRepository->find($id);

        if (!$comment) {
            return $this->redirectToRoute('app_home');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($comment->getUser() !== $user && !$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', $translator->trans('flash.accessDenied'));
            return $this->redirectToRoute('app_subject_show', ['id' => $comment->getSubject()->getId()]);
        }

        $subjectId = $comment->getSubject()->getId();
        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', $translator->trans('flash.commentDeleted'));

        return $this->redirectToRoute('app_subject_show', ['id' => $subjectId]);
    }
}