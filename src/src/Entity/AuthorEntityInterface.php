<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Allows you to use the AuthorEntitySubscriber to autoset the current author to any entity which implements this
 */
interface AuthorEntityInterface
{
    public function setAuthor(UserInterface $user): AuthorEntityInterface;
}
