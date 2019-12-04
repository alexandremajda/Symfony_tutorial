<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * This controller is for a custom Action
 * that's why he's named Action and not Controller (what would confuse symfony is namespace resolution)
 */
class ResetPasswordAction
{
    private $validator;
    private $userPasswordEncoder;
    private $entityManager;
    private $tokenManager;

    public function __construct(
        ValidatorInterface $validator, 
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager,
        JWTTokenManagerInterface $tokenManager)
    {
        $this->validator = $validator;
        $this->userPasswordEncoder = $encoder;
        $this->entityManager = $entityManager;
        $this->tokenManager = $tokenManager;
    }
    public function __invoke(User $data)
    {
        // This fonction allows you to invoke the object instance as a function
        // $reset = new ResetPasswordAction();
        // $reset();

        // var_dump($data->getNewPassword(), $data->getNewRetypedPassword(), $data->getOldPassword());die;
        $this->validator->validate($data);

        $data->setPassword(
            $this->userPasswordEncoder->encodePassword(
                $data, $data->getNewPassword()
            )
        );

        // After changing password, old tokens are still valid
        $data->setPasswordChangeDate(time());

        $this->entityManager->flush(); 
        
        $token = $this->tokenManager->create($data);

        return new JsonResponse(['token'=>$token]);
        // Validation is only called after we return the data from this action
        // Entity is persist only if validation pass !
        
    }
}
