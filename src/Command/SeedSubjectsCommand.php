<?php

namespace App\Command;

use App\Entity\Subject;
use App\Repository\SubjectRepository;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-subjects',
    description: 'Crée ou met à jour des sujets complets (nom, description, image, contenu) depuis un fichier de données, sans purger la base.',
)]
class SeedSubjectsCommand extends Command
{
    public function __construct(
        private SubjectRepository $subjectRepository,
        private ThemeRepository $themeRepository,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'themeSlug',
                InputArgument::REQUIRED,
                'Slug du thème auquel rattacher les sujets (ex: dinosaures)'
            )
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Nom du fichier de données dans src/DataFixtures/ContentData/ (ex: dinosaures_subjects)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $themeSlug = $input->getArgument('themeSlug');
        $fileName = preg_replace('/\.php$/', '', $input->getArgument('file'));

        $theme = $this->themeRepository->findOneBy(['slug' => $themeSlug]);
        if (!$theme) {
            $io->error(sprintf('Aucun thème trouvé avec le slug "%s".', $themeSlug));
            return Command::FAILURE;
        }

        $path = __DIR__ . '/../DataFixtures/ContentData/' . $fileName . '.php';
        if (!file_exists($path)) {
            $io->error(sprintf('Fichier introuvable : %s', $path));
            return Command::FAILURE;
        }

        $data = require $path;
        if (!is_array($data)) {
            $io->error('Le fichier doit retourner un tableau de sujets.');
            return Command::FAILURE;
        }

        $io->title(sprintf('Création/mise à jour des sujets pour le thème "%s"', $theme->getName()));

        $created = 0;
        $updated = 0;

        foreach ($data as $entry) {
            $slug = $entry['slug'] ?? null;
            if (!$slug) {
                $io->warning('Entrée ignorée : slug manquant.');
                continue;
            }

            $subject = $this->subjectRepository->findOneBy(['slug' => $slug]);
            $isNew = false;

            if (!$subject) {
                $subject = new Subject();
                $subject->setSlug($slug);
                $isNew = true;
            }

            $subject->setName($entry['name'] ?? $slug);
            $subject->setDescription($entry['description'] ?? '');
            $subject->setContent($entry['content'] ?? '');
            $subject->setImage($entry['image'] ?? null);
            $subject->setTheme($theme);

            if ($isNew) {
                $this->entityManager->persist($subject);
                $created++;
                $io->writeln(sprintf('  + %s (créé)', $slug));
            } else {
                $updated++;
                $io->writeln(sprintf('  ✔ %s (mis à jour)', $slug));
            }
        }

        $this->entityManager->flush();

        $io->newLine();
        $io->success(sprintf('%d sujet(s) créé(s), %d sujet(s) mis à jour.', $created, $updated));

        return Command::SUCCESS;
    }
}
