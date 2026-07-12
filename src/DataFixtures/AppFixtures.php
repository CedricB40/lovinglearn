<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * IMPORTANT — Après avoir rechargé ces fixtures (doctrine:fixtures:load),
 * les thèmes existent mais sont VIDES de sujets. Pour peupler le contenu
 * éducatif, exécuter ensuite pour chaque thème concerné :
 *
 *   php bin/console app:seed-subjects espace espace_subjects
 *   php bin/console app:seed-subjects dinosaures dinosaures_subjects
 *   (idem pour super-heros / corps-humain une fois leur contenu rédigé)
 *
 * Les fichiers de données correspondants se trouvent dans
 * src/DataFixtures/ContentData/.
 */
class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // ===== THÈMES =====
        $themes = [
            ['name' => 'Espace', 'slug' => 'espace', 'image' => '🚀', 'color' => '#092371', 'description' => 'Découvre les planètes, les étoiles et l\'univers !'],
            ['name' => 'Dinosaures', 'slug' => 'dinosaures', 'image' => '🦕', 'color' => '#1cca47', 'description' => 'Découvre les dinosaures qui ont peuplé la Terre !'],
            ['name' => 'Super-héros', 'slug' => 'super-heros', 'image' => '🦸', 'color' => '#fb0909', 'description' => 'Découvre les super-héros et leurs pouvoirs !'],
            ['name' => 'Corps humain', 'slug' => 'corps-humain', 'image' => '❤️', 'color' => '#bfd41c', 'description' => 'Découvre le fonctionnement du corps humain !'],
        ];

        foreach ($themes as $themeData) {
            $theme = new Theme();
            $theme->setName($themeData['name']);
            $theme->setSlug($themeData['slug']);
            $theme->setImage($themeData['image']);
            $theme->setColor($themeData['color']);
            $theme->setDescription($themeData['description']);
            $manager->persist($theme);
        }

        // ===== SUJETS =====
        // Volontairement absent d'ici — voir le commentaire en tête de fichier.
        // Utiliser la commande app:seed-subjects après le chargement des fixtures.

        // ===== USERS =====
        $admin = new User();
        $admin->setEmail('admin@lovinglearn.be');
        $admin->setFirstname('Admin');
        $admin->setLastname('LovingLearn');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        $usersData = [
            ['email' => 'user1@lovinglearn.be', 'firstname' => 'Cédric',   'lastname' => 'Bazin'],
            ['email' => 'user2@lovinglearn.be', 'firstname' => 'Léandric', 'lastname' => 'Bazin'],
            ['email' => 'user3@lovinglearn.be', 'firstname' => 'Ludovic',  'lastname' => 'Bazin'],
            ['email' => 'user4@lovinglearn.be', 'firstname' => 'Mariam',   'lastname' => 'Omri'],
            ['email' => 'user5@lovinglearn.be', 'firstname' => 'Imran',    'lastname' => 'Omri'],
            ['email' => 'user6@lovinglearn.be', 'firstname' => 'Rayan',    'lastname' => 'Omri'],
            ['email' => 'user7@lovinglearn.be', 'firstname' => 'Sami',     'lastname' => 'Omri'],
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setFirstname($userData['firstname']);
            $user->setLastname($userData['lastname']);
            $user->setRoles([]);
            $user->setIsVerified(true);
            $user->setPassword($this->hasher->hashPassword($user, 'user123'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
