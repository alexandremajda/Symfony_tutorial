<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;

/**
 * When you change your password, your token is refresh
 * So this class allows you to invalid the previous token
 */
class TokenAuthenticator extends JWTTokenAuthenticator
{
    /**
     * @param PreAuthenticationJWTUserToken $preAuthToken
     * @param UserProviderInterface $userProvider
     * @return void
     */
    public function getUser($preAuthToken, UserProviderInterface $userProvider)
    {
        $user = parent::getUser(
            $preAuthToken,
            $userProvider
        );

        /**
         * Check if the token was generated before changing the password
         * Throw the Token expired if true
         */ 
        if ($user->getPasswordChangeDate() &&
            $preAuthToken->getPayload()['iat'] < $user->getPasswordChangeDate())
            throw new ExpiredTokenException();

        return $user;
    }
}
