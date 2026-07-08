<?php

namespace App\Controller;

use App\Entity\Subject;
use App\Entity\User;
use App\Form\SubjectType;
use App\Repository\SubjectRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SubjectController extends AbstractController
{
    #[Route(path: '/subject/', name: 'app_subject_index')]
    public function index(ThemeRepository $themeRepository): Response
    {
        return $this->render('subject/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/subject/new', name: 'app_subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, TranslatorInterface $translator, ThemeRepository $themeRepository): Response
    {
        $subject = new Subject();
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($subject->getName())->lower();
            $subject->setSlug($slug);
            $entityManager->persist($subject);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('flash.subject.created', ['%name%' => $subject->getName()]));
            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subject/new.html.twig', [
            'subject' => $subject,
            'form'    => $form,
            'themes'  => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/subject/{id}', name: 'app_subject_show', methods: ['GET'])]
    public function show(Subject $subject, ThemeRepository $themeRepository, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!$user) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        if (!$user->isVerified()) {
            $this->addFlash('error', $translator->trans('flash.mustVerifyEmailSubject'));
            return $this->redirectToRoute('app_home');
        }

        return $this->render('subject/show.html.twig', [
            'subject' => $subject,
            'themes'  => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/subject/{id}/edit', name: 'app_subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Subject $subject, EntityManagerInterface $entityManager, SluggerInterface $slugger, TranslatorInterface $translator, ThemeRepository $themeRepository): Response
    {
        $form = $this->createForm(SubjectType::class, $subject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($subject->getName())->lower();
            $subject->setSlug($slug);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('flash.subject.updated', ['%name%' => $subject->getName()]));
            return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('subject/edit.html.twig', [
            'subject' => $subject,
            'form'    => $form,
            'themes'  => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/subject/{id}', name: 'app_subject_delete', methods: ['POST'])]
    public function delete(Request $request, Subject $subject, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subject->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($subject);
            $entityManager->flush();
            $this->addFlash('success', $translator->trans('flash.subject.deleted'));
        }

        return $this->redirectToRoute('app_subject_index', [], Response::HTTP_SEE_OTHER);
    }
}
