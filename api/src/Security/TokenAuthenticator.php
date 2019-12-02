<?php


namespace App\Security;


use App\Entity\Client;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\PreAuthenticationJWTUserToken;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Guard\JWTTokenAuthenticator;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends JWTTokenAuthenticator
{
    /**
     * @param PreAuthenticationJWTUserToken $preAuthToken
     * @param UserProviderInterface $userProvider
     * @return \Symfony\Component\Security\Core\User\UserInterface|void|null
     */
    public function getUser($preAuthToken, UserProviderInterface $userProvider)
    {
        /** @var Client $client */
        $client = parent::getUser($preAuthToken, $userProvider);

        //if we change password, invalidate the actual token
        if ($client->getPasswordChangeDate() !== null && $preAuthToken->getPayload()['iat'] < $client->getPasswordChangeDate()) {
            throw new ExpiredTokenException();
        }

        return $client;

    }

}
