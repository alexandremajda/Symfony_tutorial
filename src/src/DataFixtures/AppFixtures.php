<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     *  Those private variable and the construct are needed to use encryption password
     */
    private $passwordEncoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

        // This place the instance of the FakerFactory, there's no import for this
        $this->faker = \Faker\Factory::create();
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
        $this->loadComment($manager);
    }

    public function loadBlogPost(ObjectManager $manager) {

        $user = $this->getReference('blop_admin');

        // Generate 100 fakes blogPosts
        for ($i = 0; $i < 100; $i++) {
            $blogPost = new BlogPost();

            $blogPost->setAuthor($user);
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setContent($this->faker->realText());
            $blogPost->setPublished($this->faker->dateTime);
            $blogPost->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost); 

            $manager->persist($blogPost);
        }
            
        $manager->flush();
    }
    public function loadComment(ObjectManager $manager) {

        // For each BlogPost
        for ($i = 0; $i < 100; $i++) {

            // Generate a random number of comment (between 1 and 10)
            for ($j = 0; $j < rand(1, 10); $j++) {
                $comment = new Comment();
                
                $comment->setPublished($this->faker->dateTime);
                $comment->setContent($this->faker->realText());
                $comment->setAuthor($this->getReference('blop_admin'));
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
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
