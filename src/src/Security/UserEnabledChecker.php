<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;

/**
 * A simple class that check if the user account is enable
 * throws DisabledException if not
 */
class UserEnabledChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof User)
            return;

        if (!$user->getEnable())
            throw new DisabledException();
    }

    public function checkPostAuth(UserInterface $user)
    {
        
    }
}
