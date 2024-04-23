<?php



namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\Media;
use App\Entity\Profil;
use App\Entity\User;
use App\Service\Media as MediaService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;
    private MediaService $mediaService;
  
    public function __construct(UserPasswordHasherInterface $hasher,MediaService $mediaService)
    {
        $this->hasher = $hasher;
        $this->mediaService = $mediaService ;
    }



    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create();

        

        // create 5 user Profil
        for ($i = 0; $i < 5; $i++) {
            $seller = new User();
            $profil = new Profil();
            $media = new Media();
            $seller->setEmail('seller' . ($i + 1) . '@gmail.com');
            $seller->setRoles(['ROLE_PRO']);
            $seller->setFirstName('Prenom'. ($i + 1));
            $seller->setLastName('Nom'. ($i + 1));
            $seller->setPassword($this->hasher->hashPassword($seller, 'password'));
            $seller->setProfil($profil->setPseudo('user'. ($i + 1)));
            $seller->setProfil($profil->setDescription('user'. ($i + 1)));
            $image = 'mediaLieu/efc32d6013863ae809ebc0f3bf32b51a45d1bb55.png';
            $this->mediaService->media("mediaLieu/efc32d6013863ae809ebc0f3bf32b51a45d1bb55.png");
            $manager->persist($seller);
        }

        // create 10 user Organisateur
        for ($i = 0; $i < 10; $i++) {
            $buyer = new User();
            $lieu = new Lieu();
            $buyer->setEmail('buyer' . ($i + 1) . '@gmail.com');
            $buyer->setRoles(['ROLE_ORGA']);
            $buyer->setFirstName('Prenom'. ($i + 1));
            $buyer->setLastName('Nom'. ($i + 1));
            $buyer->setPassword($this->hasher->hashPassword($buyer, 'password'));
            $buyer->setLieu($lieu->setNom('user'. ($i + 1)));
            $buyer->setLieu($lieu->setAdresse('user'. ($i + 1)));
            $buyer->setLieu($lieu->setVille('user'. ($i + 1)));
            $buyer->setLieu($lieu->setCodePostal('user'. ($i + 1)));
            $buyer->setLieu($lieu->setDescription('user'. ($i + 1)));

            $manager->persist($buyer);
        }

        $manager->flush();
    }

} 
