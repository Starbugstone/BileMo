<?php
// api/src/Security/UserEnabledChecker.php

namespace App\Security;


use App\Entity\Client;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserEnabledChecker implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     * will trow an error if account is not active
     *
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof Client){
            return;
        }
        /**
         * @var Client $user
         */
        if(!$user->getActive()){
            throw new DisabledException('Account not active ');
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @throws AccountStatusException
     */
    public function checkPostAuth(UserInterface $user)
    {
        // nothing to do
    }
}