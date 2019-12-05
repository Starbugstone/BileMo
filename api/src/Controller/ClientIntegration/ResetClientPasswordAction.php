<?php
// api/src/Controller/ClientIntegration/ResetClientPasswordAction.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Exception\BadTokenException;
use Exception;


/**
 * "path"="/clients/{id}/password/reset"
 * id in the url
 * send reset token
 * send new password
 *
 * Class ResetClientPasswordAction
 * @package App\Controller\ClientIntegration
 */
class ResetClientPasswordAction
{
    Use TokenVerificationTrait;

    public function __invoke(Client $data)
    {
        //if all necessary info isn't found in payload, just stop here
        if ($data->getNewUserToken() === null || $data->getPlainPassword() === null) {
            throw new BadTokenException('Bad JSON payload');
        }

        $registeredClient = $this->getValidUser($data->getId(), $data->getNewUserToken());

        if ($registeredClient->getActive() === false) {
            throw new Exception('Account is not active, contact the administrator');
        }

        //since we cleared the cache, the $data is no longer linked to the entity so we use the registered client to update
        $registeredClient->setPlainPassword($data->getPlainPassword());

        //resetting the token to null as we have redefined the password
        $registeredClient->setNewUserToken(null);

        //After password change, old tokens are still valid
        $registeredClient->setPasswordChangeDate(time());


        return $registeredClient;

    }

}
