<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Fixtures is to generated fake datas (that allows you to tests)
 * To load fixtures -> php bin/console d:f:l -q
 */
class AppFixtures extends Fixture
{
    /**
     *  Those private variable and the construct are needed to use encryption password
     */
    private $passwordEncoder;
    private $faker;

    private const USERS = [
        [
            'username' => 'admin',
            'email' => 'admin@blog.com',
            'name' => 'adminer ecil',
            'password' => '15abcdEF',
            'roles' => [User::ROLE_SUPERADMIN]
        ],[
            'username' => 'client',
            'email' => 'client@blog.com',
            'name' => 'client ecil',
            'password' => '15abcdEF',
            'roles' => [User::ROLE_WRITER]
        ],[
            'username' => 'ecrivain',
            'email' => 'ecrivain@blog.com',
            'name' => 'ecrivain ecil',
            'password' => '15abcdEF',
            'roles' => [User::ROLE_WRITER]
        ],[
            'username' => 'random',
            'email' => 'random@blog.com',
            'name' => 'random ecil',
            'password' => '15abcdEF',
            'roles' => [User::ROLE_EDITOR]
        ],[
            'username' => 'blop',
            'email' => 'blop@blog.com',
            'name' => 'blop ecil',
            'password' => '15abcdEF',
            'roles' => [User::ROLE_ADMIN]
        ],[
            'username' => 'toto',
            'email' => 'toto@tutu.com',
            'name' => 'toto ecil',
            'password' => '15abcdEF',
            'roles' => [User::ROLE_COMMENTATOR]
        ]
    ];

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

        // Generate 100 fakes blogPosts
        for ($i = 0; $i < 100; $i++) {
            $blogPost = new BlogPost();

            $blogPost->setAuthor($this->getRandomUser($blogPost));
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

                $comment->setAuthor($this->getRandomUser($comment));
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
    public function loadUser(ObjectManager $manager) {

        foreach(self::USERS as $userFixture) {
            $user = new User();
            
            $user->setUsername($userFixture['username']);
            $user->setEmail($userFixture['email']);
            $user->setName($userFixture['name']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $userFixture['password']));
            $user->setRoles($userFixture['roles']);
            $user->setEnable(true);
    
            $this->addReference('user_' . $userFixture['username'], $user);
            
            $manager->persist($user);
        }
            
        $manager->flush(); 
    }

    public function getRandomUser($entity): User
    {
        $randomUser = self::USERS[rand(0, count(self::USERS)-1)];
        // $randomUser = self::USERS[rand(0, 4)];

        // Check if the random user can write a Blog Post, if not, call this function recursively
        if ($entity instanceof BlogPost && !count(
            array_intersect(
                $randomUser['roles'],
                [
                    User::ROLE_SUPERADMIN, 
                    User::ROLE_ADMIN, 
                    User::ROLE_WRITER
                    ]
                )
            ))
            return $this->getRandomUser($entity);
                
        // Check if the random user can write a Comment, if not, call this function recursively
        if ($entity instanceof BlogPost && !count(
            array_intersect(
                $randomUser['roles'],
                [
                    User::ROLE_SUPERADMIN, 
                    User::ROLE_ADMIN,
                    User::ROLE_WRITER,
                    User::ROLE_COMMENTATOR
                ]
            )
        ))
            return $this->getRandomUser($entity);

        return $this->getReference('user_' . $randomUser['username']);
    }
}
