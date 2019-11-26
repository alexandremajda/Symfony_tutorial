<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {

    private const POSTS = [
        [
            'id'    => 1,
            'slug'  => 'hello-world',
            'title' => 'Hello world!',
        ],
        [
            'id'    => 2,
            'slug'  => 'another-post',
            'title' => 'Another post',
        ],
        [
            'id'    => 3,
            'slug'  => 'last-example',
            'title' => 'This is the last example',
        ],
    ];
    /**
     * @Route("/", name="blog_list")
     *
     * @return void
     */
    public function list() {
        return new JsonResponse(self::POSTS);
    }

    /**
     * @Route("/{id}", name="blog_by_id", requirements={"id"="\d+"})
     *
     * @param int $id
     * @return void
     */
    public function post($id) {
        $index = array_search($id, array_column(self::POSTS, 'id'));
        return new JsonResponse(
            self::POSTS[$index]
        );
    }
    
    /**
     * @Route("/{slug}", name="blog_by_slug")
     *
     * @param String $slug
     * @return void
     */
    public function post_by_slug($slug) {
        $index = array_search($slug, array_column(self::POSTS, 'slug'));
        return new JsonResponse(
            self::POSTS[$index]
        );

    }
}

?>