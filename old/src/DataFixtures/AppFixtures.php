<?php
namespace App\DataFixtures;

use App\Entity\Biblio;
use App\Entity\Book;
use App\Entity\Cover;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $passwordEncoder;


    public function __construct(SluggerInterface $slugger, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->slugger = $slugger;
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        

        /* Utilisateurs */
        $admin = new User();
        $admin->setEmail('augirard17@gmail.com');
        $admin->setPassword($this->passwordEncoder->encodePassword($admin, 'password'));
        $admin->setRegisterAt(new \DateTime('now'))
              ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($admin);

        /*Biblio admin */
        $biblo = new Biblio();
        $biblo->setCreatedAt(new \DateTime())
              ->setTitle('Admin Biblio')
              ->setSlug(strtolower($this->slugger->slug($biblo->getTitle())))
              ->setUserOwner($admin)
        ;
        $manager->persist($biblo);
        

        for($i = 0; $i < mt_rand(10, 20); $i++) { 
            $editeur = new User();

            $editeur->setEmail($faker->email)
                    ->setRegisterAt(new \DateTime())
                    ->setPassword($this->passwordEncoder->encodePassword($editeur, 'password'))
            ;

            $biblio = new Biblio();
            $biblio->setCreatedAt(new \DateTime())
                  ->setTitle($faker->title())
                  ->setSlug(strtolower($this->slugger->slug($biblo->getTitle())))
                  ->setUserOwner($editeur);

            $editeur->setBiblio($biblio);
            $manager->persist($biblio);
            $manager->persist($editeur);

        }

        $manager->flush();
    }
}
