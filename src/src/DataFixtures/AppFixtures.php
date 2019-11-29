<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * This method generates a dataset and flush it to the database
     * To run the preset, use :
     * `php bin/console doctrine:fixtures:load`
     *
     * @param ObjectManager $manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        $blogPost = new BlogPost();

        $blogPost->setAuthor("a.m.");
        $blogPost->setContent("This is a randomised content");
        $blogPost->setPublished(new \DateTime('2019-11-28 15:00:00'));
        $blogPost->setSlug('a-random-slug');
        $blogPost->setTitle("Toutouyoutou");

        $manager->persist($blogPost);
        $blogPost = new BlogPost();

        $blogPost->setAuthor("toto");
        $blogPost->setContent("This is another randomised content");
        $blogPost->setPublished(new \DateTime('2019-11-28 16:00:00'));
        $blogPost->setSlug('another-random-slug');
        $blogPost->setTitle("ananas");

        $manager->persist($blogPost);
        $blogPost = new BlogPost();

        $blogPost->setAuthor("oeinv");
        $blogPost->setContent("This is agagin a randomised content");
        $blogPost->setPublished(new \DateTime('2019-11-28 12:00:00'));
        $blogPost->setSlug('again-random-slug');
        $blogPost->setTitle("plup");

        $manager->persist($blogPost);
        $blogPost = new BlogPost();

        $blogPost->setAuthor("blop");
        $blogPost->setContent("This is another randomised content. again.");
        $blogPost->setPublished(new \DateTime('2019-11-28 13:00:00'));
        $blogPost->setSlug('another-random-slug-again');
        $blogPost->setTitle("bibidibi");

        $manager->persist($blogPost);
        $manager->flush();
    }
}
