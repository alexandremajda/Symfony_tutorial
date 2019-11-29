<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;    
    }
    /**
     * This method generates a dataset and flush it to the database
     * To run the preset, use :
     * `php bin/console doctrine:fixtures:load` or `php bin/console d:f:l -q`
     * -q for quiet 
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUser($manager);
        $this->loadBlogPost($manager);
    }

    public function loadBlogPost(ObjectManager $manager) {

        $user = $this->getReference('blop_admin');
        $blogPost = new BlogPost();

        $blogPost->setAuthor($user);
        $blogPost->setContent("This is a randomised content");
        $blogPost->setPublished(new \DateTime('2019-11-28 15:00:00'));
        $blogPost->setSlug('a-random-slug');
        $blogPost->setTitle("Toutouyoutou");

        $manager->persist($blogPost);
        $blogPost = new BlogPost();

        $blogPost->setAuthor($user);
        $blogPost->setContent("This is another randomised content");
        $blogPost->setPublished(new \DateTime('2019-11-28 16:00:00'));
        $blogPost->setSlug('another-random-slug');
        $blogPost->setTitle("ananas");

        $manager->persist($blogPost);
        $blogPost = new BlogPost() ;

        $blogPost->setAuthor($user);
        $blogPost->setContent("This is agagin a randomised content");
        $blogPost->setPublished(new \DateTime('2019-11-28 12:00:00'));
        $blogPost->setSlug('again-random-slug');
        $blogPost->setTitle("plup");

        $manager->persist($blogPost);
        $blogPost = new BlogPost();

        $blogPost->setAuthor($user);
        $blogPost->setContent("This is another randomised content. again.");
        $blogPost->setPublished(new \DateTime('2019-11-28 13:00:00'));
        $blogPost->setSlug('another-random-slug-again');
        $blogPost->setTitle("bibidibi");

        $manager->persist($blogPost);
        $manager->flush();
    }
    public function loadComment() {
        
    }
    public function loadUser(ObjectManager $manager) {
        $user = new User();
        
        $user->setUsername("blop");
        $user->setEmail("blop@blup.bloup");
        $user->setName("Blou");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "1234"));

        $this->addReference('blop_admin', $user);
        
        $manager->persist($user);
        $manager->flush(); 
    }
}
