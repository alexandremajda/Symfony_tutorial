<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController {


    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 10}, requirements={"page"="\d+"})
     *
     * Route ->
     *  => URI
     *  name => Name of the route
     *  defaults => defaults value for params (Json format type)
     *  requirements => the param must match the requirements, here, param page must be an int
     *  
     * The JsonResponse returns ALWAYS a Response object (It's a symfony Basic's)
     * @return Response
     */
    public function list($page = 1, Request $request) {
        
        $limit = $request->get("limit", 10);

        /**
         * Retrieve the right repository to use 
         */
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        
        /**
         * the `findAll` function of the repository return a list of all records in the associated table from the database
         * It's the new dataset
         */
        $items = $repository->findAll();


        return $this->json([
            'page' => $page,
            'limit' => $limit,
            'data' => $items,
        ]);
    }

    /**
     * @Route("/list_by_id", name="blog_list_by_id")
     *
     * @return void
     */
    public function list_url_by_id() {
        
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json([
            /**
             * This array_map takes a function that handle every record in the self::POSTS var
             * and generate urls using id for each of them
             * The url is just generated, and not reached. So it's normal that there's nothing in the response block
             */
            'data' => array_map(function ($items) {
                return $this->generateUrl("blog_by_id", ['id'=>$items['id']]);
            }, $items)
        ]);
    }

    /**
     * @Route("/list_by_slug", name="blog_list_by_slug")
     *
     * @return void
     */
    public function list_url_by_slug() {

        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json([

            /**
             * This array_map takes a function that handle every record in the self::POSTS var
             * and generate urls using slug for each of them
             * The url is just generated, and not reached. So it's normal that there's nothing in the response block
             */
            'data' => array_map(function ($items) {
                return $this->generateUrl("blog_by_slug", ['slug'=>$items['slug']]);
            }, $items)
        ]);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
     *
     * @param int $id
     * @return void
     */
    public function post($id) {

        $repository = $this->getDoctrine()->getRepository(BlogPost::class);

        /**
         * Fetch an entity by Id to return the right one, using find
         */
        $item = $repository->find($id);

        return $this->json($item);
    }
    
    /**
     * @Route("/post/{slug}", name="blog_by_slug")
     * @ParamConverter("post", class="App:BlogPost", options={"mapping" : {"slug": "author"}})
     * 
     * The paramConverter (must be imported) line above specify that 
     * the post argument is from type BlogPost and map by the author name
     * 
     * @param BlogPost $post
     * @return void
     */
    public function post_by_slug($post) {
    
        // $repository = $this->getDoctrine()->getRepository(BlogPost::class);

        /**
         * Fetch an entity by any other field than id,
         * using findBy to fetch all elements matching
         * using findOneBy to fetch only the first one
         */
        // $item = $repository->findOneBy(['slug' => $slug]);

        // return $this->json(
        //     $item
        // );

        /**
         * Equivalent of the findOneBy(['slug' => 'slug']);
         * Where slug is map in the ParamConverter options (here, slug is author)
         */ 
        return $this->json($post);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     * 
     *  methods => precise the method allowed for this route, here, only post method can reach the add route
     *
     * @param Request $request
     * @return void
     */
    public function add(Request $request) {

        /**
         * @var Serializer $serializer
         * 
         * Can be done by running the CLI command `composer require serializer`
         */
        $serializer = $this->get('serializer');

        /**
         *  request->getContent() => the request body (which contain the json to deserialize)
         *  BlogPost::class => The Type towards which we deserialize data received in the request's body
         *  json => format of data received
         */
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();

        /**
         * Use the EntityManager to persist the received json to the choosen class
         */
        $em->persist($blogPost);

        /**
         * Save data changes
         */
        $em->flush();
        
        /**
         * Return an instance of the $blogPost serialized data 
         */
        return $this->json($blogPost);
    }
}

?>