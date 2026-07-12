<?php

namespace App\Command;

use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-subject-content',
    description: 'Met à jour le champ "content" des sujets existants depuis un fichier de données, sans purger la base.',
)]
class SeedSubjectContentCommand extends Command
{
    public function __construct(
        private SubjectRepository $subjectRepository,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'Nom du fichier de contenu dans src/DataFixtures/ContentData/ (ex: espace_content ou dinosaures_content)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fileName = $input->getArgument('file');
        // On retire une éventuelle extension .php passée par erreur
        $fileName = preg_replace('/\.php$/', '', $fileName);

        $path = __DIR__ . '/../DataFixtures/ContentData/' . $fileName . '.php';

        if (!file_exists($path)) {
            $io->error(sprintf('Fichier introuvable : %s', $path));
            return Command::FAILURE;
        }

        $data = require $path;

        if (!is_array($data)) {
            $io->error('Le fichier de contenu doit retourner un tableau (slug => contenu).');
            return Command::FAILURE;
        }

        $io->title(sprintf('Mise à jour du contenu depuis "%s.php"', $fileName));

        $updated = 0;
        $notFound = [];

        foreach ($data as $slug => $content) {
            $subject = $this->subjectRepository->findOneBy(['slug' => $slug]);

            if (!$subject) {
                $notFound[] = $slug;
                continue;
            }

            $subject->setContent($content);
            $updated++;
            $io->writeln(sprintf('  ✔ %s', $slug));
        }

        $this->entityManager->flush();

        $io->newLine();
        $io->success(sprintf('%d sujet(s) mis à jour.', $updated));

        if (!empty($notFound)) {
            $io->warning(sprintf(
                'Slug(s) non trouvé(s) en base (ignoré(s)) : %s',
                implode(', ', $notFound)
            ));
        }

        return Command::SUCCESS;
    }
}
