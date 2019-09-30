<?php
// api/src/Controller/ClientIntegration/ResetClientPasswordAction.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Exception\BadTokenException;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\Annotation\Route;


/**
 * "path"="/reset_client/{id}"
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
    //TODO: Document in swagger / (hail) hydra format


    public function __invoke(Client $data)
    {

        $registeredClient = $this->getValidUser($data->getId(), $data->getNewUserToken());

        if ($registeredClient->getActive() === false) {
            throw new Exception('Account is not active, contact the administrator');
        }

        //since we cleared the cache, the $data is no longer linked to the entity so we use the registered client to update
        $registeredClient->setPlainPassword($data->getPlainPassword());

        //resetting the token to null as we have redefined the password
        $registeredClient->setNewUserToken(null);

        return $registeredClient;

    }

}
