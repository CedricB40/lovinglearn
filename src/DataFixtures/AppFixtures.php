<?php

namespace App\DataFixtures;

use App\Entity\Theme;
use App\Entity\Subject;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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

        $themeObjects = [];
        foreach ($themes as $themeData) {
            $theme = new Theme();
            $theme->setName($themeData['name']);
            $theme->setSlug($themeData['slug']);
            $theme->setImage($themeData['image']);
            $theme->setColor($themeData['color']);
            $theme->setDescription($themeData['description']);
            $manager->persist($theme);
            $themeObjects[$themeData['name']] = $theme;
        }

        // ===== SUJETS ESPACE =====
        $subjects = [
            ['name' => 'Soleil', 'slug' => 'soleil', 'description' => 'étoile naine jaune', 'content' => 'Le Soleil est l\'étoile de type naine jaune du Système solaire.', 'image' => '1-soleil.png', 'theme' => 'Espace'],
            ['name' => 'Mercure', 'slug' => 'mercure', 'description' => 'planète tellurique', 'content' => 'Mercure est la planète la plus proche du Soleil et la plus petite du Système solaire.', 'image' => '2-mercure.png', 'theme' => 'Espace'],
            ['name' => 'Vénus', 'slug' => 'venus', 'description' => 'planète tellurique', 'content' => 'Vénus est la deuxième planète du Système solaire par ordre d\'éloignement au Soleil.', 'image' => '3-venus.png', 'theme' => 'Espace'],
            ['name' => 'Terre', 'slug' => 'terre', 'description' => 'planète tellurique', 'content' => 'La Terre est la troisième planète par ordre d\'éloignement au Soleil et la plus grande des planètes telluriques.', 'image' => '4-terre.png', 'theme' => 'Espace'],
            ['name' => 'Mars', 'slug' => 'mars', 'description' => 'planète tellurique', 'content' => 'Mars est la quatrième planète du Système solaire par ordre croissant de la distance au Soleil.', 'image' => '5-mars.png', 'theme' => 'Espace'],
            ['name' => 'Jupiter', 'slug' => 'jupiter', 'description' => 'planète géante gazeuse', 'content' => 'Jupiter est la cinquième planète du Système solaire et la plus grande.', 'image' => '6-jupiter.png', 'theme' => 'Espace'],
            ['name' => 'Saturne', 'slug' => 'saturne', 'description' => 'planète géante gazeuse', 'content' => 'Saturne est la sixième planète du Système solaire et la deuxième plus grande.', 'image' => '7-saturne.png', 'theme' => 'Espace'],
            ['name' => 'Uranus', 'slug' => 'uranus', 'description' => 'planète géante de glace', 'content' => 'Uranus est la septième planète du Système solaire en partant du Soleil.', 'image' => '8-uranus.png', 'theme' => 'Espace'],
            ['name' => 'Neptune', 'slug' => 'neptune', 'description' => 'planète géante de glace', 'content' => 'Neptune est la huitième planète du Système solaire en partant du Soleil.', 'image' => '9-neptune.png', 'theme' => 'Espace'],
            ['name' => 'Pluton', 'slug' => 'pluton', 'description' => 'planète naine', 'content' => 'Pluton est une planète naine du Système solaire située dans la ceinture de Kuiper.', 'image' => '10-pluton.png', 'theme' => 'Espace'],
        ];

        foreach ($subjects as $subjectData) {
            $subject = new Subject();
            $subject->setName($subjectData['name']);
            $subject->setSlug($subjectData['slug']);
            $subject->setDescription($subjectData['description']);
            $subject->setContent($subjectData['content']);
            $subject->setImage($subjectData['image']);
            $subject->setTheme($themeObjects[$subjectData['theme']]);
            $manager->persist($subject);
        }

        // ===== USERS =====
        // Admin
        $admin = new User();
        $admin->setEmail('admin@lovinglearn.be');
        $admin->setFirstname('Admin');
        $admin->setLastname('LovingLearn');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsVerified(true);
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin123'));
        $manager->persist($admin);

        // Users normaux
        $usersData = [
            ['email' => 'user1@lovinglearn.be', 'firstname' => 'Cédric', 'lastname' => 'Bazin'],
            ['email' => 'user2@lovinglearn.be', 'firstname' => 'Léandric', 'lastname' => 'Bazin'],
            ['email' => 'user3@lovinglearn.be', 'firstname' => 'Ludovic', 'lastname' => 'Bazin'],
            ['email' => 'user4@lovinglearn.be', 'firstname' => 'Mariam', 'lastname' => 'Omri'],
            ['email' => 'user5@lovinglearn.be', 'firstname' => 'Imran', 'lastname' => 'Omri'],
            ['email' => 'user6@lovinglearn.be', 'firstname' => 'Rayan', 'lastname' => 'Omri'],
            ['email' => 'user7@lovinglearn.be', 'firstname' => 'Sami', 'lastname' => 'Omri'],
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