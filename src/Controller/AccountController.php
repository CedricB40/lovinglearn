<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AccountController extends AbstractController
{
    #[Route(path: '/account', name: 'app_account')]
    public function index(TranslatorInterface $translator, ThemeRepository $themeRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        return $this->render('account/index.html.twig', [
            'themes' => $themeRepository->findAll(),
        ]);
    }

    #[Route(path: '/account/edit', name: 'app_account_edit')]
    public function edit(Request $request, EntityManagerInterface $em, TranslatorInterface $translator, ThemeRepository $themeRepository): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('error', $translator->trans('flash.mustLogin'));
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $em->flush();
            $this->addFlash('success', $translator->trans('flash.accountUpdated'));
            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/edit.html.twig', [
            'form'   => $form,
            'themes' => $themeRepository->findAll(),
        ]);
    }
}