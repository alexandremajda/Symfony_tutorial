<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;


/**
 * Define a custom ApiResource to check the new user's confirmation token
 * if itemOperation is empty, disable all itemOperations default routes
 * 
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "path"="/users/confirm"
 *          }
 *      },
 *      itemOperations={}
 * )
 */
class UserConfirmation
{

    /**
     * The token has a size of 30 chars long, so need to make length of this variable to 30
     * 
     * @Assert\NotBlank()
     * @Assert\Length(min=30, max=30)
     */
    public $confirmationToken;
}
