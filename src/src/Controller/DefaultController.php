<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController {
        
/**
 * @Route("/", name="default_index")
 *
 * @return JsonResponse
 */
    public function index() {
        return new JsonResponse([
            'action' => 'index',
            'time' => time()
        ]);
    }
}

?>