<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class UploadImageAction
{
    private $formFactory;
    private $entityManager;
    private $validator;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ) {
        $this->formFactory = $formFactory;    
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function __invoke(Request $request)
    {
        //Create a new Image Instance
        $image = new Image();

        // Validate the form
        $form = $this->formFactory->create(null, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Persist Image entity
            $this->entityManager->persist($image);
            $this->entityManager->flush();

            $image->setFile(null);

            return $image;
        }

        // Uploading Background with vich

        // Throw a validation exception if something went wrong, setted by default to avoid specifics behaviors
        throw new ValidationException(
            $this->validator->validate($image)
        );
    }
}
