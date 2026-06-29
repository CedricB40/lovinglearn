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

        // ===== SUJETS =====
        $subjects = [
            // --- Espace ---
            ['name' => 'Soleil',   'slug' => 'soleil',   'description' => 'Étoile naine jaune au centre du système solaire',          'content' => 'Le Soleil est l\'étoile de type naine jaune du Système solaire. Il représente 99,86% de la masse totale du système solaire.',                                                                         'image' => '1-soleil.png',   'theme' => 'Espace'],
            ['name' => 'Mercure',  'slug' => 'mercure',  'description' => 'Planète tellurique la plus proche du Soleil',              'content' => 'Mercure est la planète la plus proche du Soleil et la plus petite du Système solaire. Une année sur Mercure dure seulement 88 jours terrestres.',                                                   'image' => '2-mercure.png',  'theme' => 'Espace'],
            ['name' => 'Vénus',    'slug' => 'venus',    'description' => 'Planète tellurique la plus chaude du système solaire',     'content' => 'Vénus est la deuxième planète du Système solaire. C\'est la planète la plus chaude avec des températures atteignant 465°C, malgré qu\'elle ne soit pas la plus proche du Soleil.',           'image' => '3-venus.png',    'theme' => 'Espace'],
            ['name' => 'Terre',    'slug' => 'terre',    'description' => 'Notre planète bleue, seule planète abritant la vie',       'content' => 'La Terre est la troisième planète par ordre d\'éloignement au Soleil. C\'est la seule planète connue à abriter la vie. Elle est recouverte à 71% d\'eau.',                                     'image' => '4-terre.png',    'theme' => 'Espace'],
            ['name' => 'Mars',     'slug' => 'mars',     'description' => 'La planète rouge, voisine de la Terre',                   'content' => 'Mars est la quatrième planète du Système solaire. Elle doit sa couleur rouge à l\'oxyde de fer présent à sa surface. Elle possède le plus grand volcan du système solaire : l\'Olympus Mons.', 'image' => '5-mars.png',     'theme' => 'Espace'],
            ['name' => 'Jupiter',  'slug' => 'jupiter',  'description' => 'La plus grande planète du système solaire',               'content' => 'Jupiter est la cinquième planète du Système solaire et la plus grande. Sa Grande Tache Rouge est une tempête qui dure depuis plus de 350 ans. Elle possède 95 lunes connues.',                  'image' => '6-jupiter.png',  'theme' => 'Espace'],
            ['name' => 'Saturne',  'slug' => 'saturne',  'description' => 'Planète géante gazeuse célèbre pour ses anneaux',         'content' => 'Saturne est la sixième planète du Système solaire. Ses anneaux sont composés de glace et de roches. Elle est si légère qu\'elle flotterait sur l\'eau.',                                         'image' => '7-saturne.png',  'theme' => 'Espace'],
            ['name' => 'Uranus',   'slug' => 'uranus',   'description' => 'Planète géante de glace qui tourne sur le côté',          'content' => 'Uranus est la septième planète du Système solaire. Elle est unique car elle tourne sur le côté avec une inclinaison de 98 degrés. Ses températures atteignent -224°C.',                            'image' => '8-uranus.png',   'theme' => 'Espace'],
            ['name' => 'Neptune',  'slug' => 'neptune',  'description' => 'La planète la plus éloignée du Soleil',                   'content' => 'Neptune est la huitième planète du Système solaire. Elle possède les vents les plus violents du système solaire, pouvant atteindre 2 100 km/h. Une année sur Neptune dure 165 ans terrestres.',   'image' => '9-neptune.png',  'theme' => 'Espace'],
            ['name' => 'Pluton',   'slug' => 'pluton',   'description' => 'Planète naine aux confins du système solaire',            'content' => 'Pluton est une planète naine du Système solaire située dans la ceinture de Kuiper. Elle a été déclassée de planète en 2006 par l\'Union Astronomique Internationale.',                             'image' => '10-pluton.png',  'theme' => 'Espace'],

            // --- Dinosaures ---
            ['name' => 'Tyrannosaure',       'slug' => 'tyrannosaure',       'description' => 'Le roi des dinosaures carnivores',                       'content' => 'Le Tyrannosaure Rex est l\'un des plus grands prédateurs terrestres ayant jamais existé. Il mesurait jusqu\'à 13 mètres de long et pesait jusqu\'à 8 tonnes. Ses petits bras étaient en réalité très puissants.',                                        'image' => '1-trex.png',               'theme' => 'Dinosaures'],
            ['name' => 'Tricératops',        'slug' => 'triceratops',        'description' => 'Dinosaure herbivore aux trois cornes emblématiques',     'content' => 'Le Tricératops était un dinosaure herbivore qui vivait il y a environ 68 millions d\'années. Ses trois cornes et sa grande collerette osseuse lui servaient probablement à se défendre contre les prédateurs comme le T-Rex.',          'image' => '2-triceratops.png',        'theme' => 'Dinosaures'],
            ['name' => 'Diplodocus',         'slug' => 'diplodocus',         'description' => 'L\'un des plus longs dinosaures herbivores',             'content' => 'Le Diplodocus était un immense dinosaure herbivore pouvant atteindre 27 mètres de long. Son long cou lui permettait d\'atteindre la végétation en hauteur. Il vivait il y a environ 150 millions d\'années.',                              'image' => '3-diplodocus.png',         'theme' => 'Dinosaures'],
            ['name' => 'Stégosaure',         'slug' => 'stegosaure',         'description' => 'Dinosaure herbivore aux plaques dorsales distinctives', 'content' => 'Le Stégosaure est reconnaissable à ses grandes plaques osseuses sur le dos et ses piques à la queue. Ces plaques pouvaient servir à réguler sa température. Il vivait il y a environ 155 millions d\'années.',                          'image' => '4-stegosaure.png',         'theme' => 'Dinosaures'],
            ['name' => 'Vélociraptor',       'slug' => 'velociraptor',       'description' => 'Petit dinosaure carnivore très agile et intelligent',   'content' => 'Le Vélociraptor était un petit dinosaure carnivore d\'environ 1,8 mètre de long. Contrairement à ce qu\'on voit dans les films, il était recouvert de plumes. Il chassait probablement en solitaire.',                                  'image' => '5-velociraptor.png',       'theme' => 'Dinosaures'],
            ['name' => 'Ankylosaure',        'slug' => 'ankylosaure',        'description' => 'Dinosaure herbivore blindé comme un char d\'assaut',    'content' => 'L\'Ankylosaure était un dinosaure herbivore recouvert d\'une armure osseuse épaisse. Sa queue en forme de massue pouvait briser les os de ses prédateurs. Il était si lourd qu\'il ne pouvait pas se retourner facilement.',            'image' => '6-ankylosaure.png',        'theme' => 'Dinosaures'],
            ['name' => 'Spinosaure',         'slug' => 'spinosaure',         'description' => 'Le plus grand dinosaure carnivore connu',               'content' => 'Le Spinosaure était probablement le plus grand dinosaure carnivore, plus grand même que le T-Rex. Il mesurait jusqu\'à 15 mètres. Sa grande voile dorsale est caractéristique. Il se nourrissait principalement de poissons.',        'image' => '7-spinosaure.png',         'theme' => 'Dinosaures'],
            ['name' => 'Pachycéphalosauré',  'slug' => 'pachycephalosaure',  'description' => 'Dinosaure au crâne épais comme un casque',              'content' => 'Le Pachycéphalosaure est célèbre pour son crâne épais de 25 cm. Les mâles s\'affrontaient probablement en se donnant des coups de tête. Il était bipède et mesurait environ 4,5 mètres.',                                         'image' => '8-pachycephalosaure.png',  'theme' => 'Dinosaures'],
            ['name' => 'Allosaure',          'slug' => 'allosaure',          'description' => 'Redoutable prédateur du Jurassique',                    'content' => 'L\'Allosaure était l\'un des principaux prédateurs du Jurassique. Il mesurait jusqu\'à 12 mètres et chassait en groupe. Ses mâchoires pouvaient s\'ouvrir très largement pour mordre ses proies.',                                   'image' => '9-allosaure.png',          'theme' => 'Dinosaures'],
            ['name' => 'Parasaurolophus',    'slug' => 'parasaurolophus',    'description' => 'Dinosaure herbivore à la crête tubulaire distinctive', 'content' => 'Le Parasaurolophus est reconnaissable à sa longue crête tubulaire creuse sur la tête. Cette crête lui permettait d\'émettre des sons pour communiquer avec ses congénères. Il vivait il y a environ 76 millions d\'années.',         'image' => '10-parasaurolophus.png',   'theme' => 'Dinosaures'],
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