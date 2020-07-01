<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

// rajout de l'entity User pour le crytage de mot de passe
use App\Entity\User;
use App\Entity\Article;


// rajout du USE pour encoder des mots de passe en base
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**rajouter de l'encoder à partir de https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html**/
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    /****/
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('Jessica Kuijer');
        $user->setEmail('jessicakuijer@me.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setNom('Kuijer');
        $user->setPrenom('Jessica');

        $password = $this->encoder->encodePassword($user, '684500');
        $user->setPassword($password);

        $user2 = new User();
        $user2->setUsername('Edith Berny');
        $user2->setEmail('bernyedith.db@gmail.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setNom('Berny');
        $user2->setPrenom('Edith');

        $password = $this->encoder->encodePassword($user2, '181278');
        $user2->setPassword($password);

        $user3 = new User();
        $user3->setUsername('Jessika2001');
        $user3->setEmail('jessicakuijer2@me.com');
        $user3->setRoles(['ROLE_COMMENTATEUR']);
        $user3->setNom('Kuyeren');
        $user3->setPrenom('Jess');

        $password = $this->encoder->encodePassword($user3, '68450000');
        $user3->setPassword($password);
        

        // $product = new Product();
        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);

        // je prépare des références
        $this->addReference('user-admin', $user);
        $this->addReference('user-user', $user2);
        $this->addReference('user-commentateur', $user3);
        

        /** PARTIE ARTICLE**/

        $article = new Article();
        $article->setTitre('Article 1');
        $article->setResume('Article 1');
        $article->setAuteur('Article 1');
        $article->setContenu('Article 1');
        $article->setCreatedAt(new \Datetime());
        $article->setImage('image_par_defaut.jpg');
        $article->setUser($this->getReference('user-admin'));

        $article2 = new Article();
        $article2->setTitre('Article 2');
        $article2->setResume('Article 2');
        $article2->setAuteur('Article 2');
        $article2->setContenu('Article 2');
        $article2->setCreatedAt(new \Datetime());
        $article2->setImage('image_par_defaut.jpg');
        $article2->setUser($this->getReference('user-admin'));

        $article3 = new Article();
        $article3->setTitre('Article 3');
        $article3->setResume('Article 3');
        $article3->setAuteur('Article 3');
        $article3->setContenu('Article 3');
        $article3->setCreatedAt(new \Datetime());
        $article3->setImage('image_par_defaut.jpg');
        $article3->setUser($this->getReference('user-user'));

        $article4 = new Article();
        $article4->setTitre('Article 4');
        $article4->setResume('Article 4');
        $article4->setAuteur('Article 4');
        $article4->setContenu('Article 4');
        $article4->setCreatedAt(new \Datetime());
        $article4->setImage('image_par_defaut.jpg');
        $article4->setUser($this->getReference('user-user'));

        $article5 = new Article();
        $article5->setTitre('Article 5');
        $article5->setResume('Article 5');
        $article5->setAuteur('Article 5');
        $article5->setContenu('Article 5');
        $article5->setCreatedAt(new \Datetime());
        $article5->setImage('image_par_defaut.jpg');
        $article5->setUser($this->getReference('user-user'));

        $manager->persist($article);
        $manager->persist($article2);
        $manager->persist($article3);
        $manager->persist($article4);
        $manager->persist($article5);
        $manager->flush();
    }
}
