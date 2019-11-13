<?php
// api/src/Controller/ClientIntegration/VerifyClientTokenAction.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Exception\BadTokenException;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Exception;

/**
 * "path"="/activate_client/{id}"
 *
 * Class ActivateClientPasswordAction
 * @package App\Controller\ClientIntegration
 */
class ActivateClientPasswordAction
{
    Use TokenVerificationTrait;

    /**
     *
     * @param Client $data
     * @return Client
     * @throws BadTokenException
     * @throws MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws Exception
     */
    public function __invoke(Client $data): Client
    {
        //if all necessary info isn't found in payload, just stop here
        if($data->getNewUserToken() === null || $data->getPlainPassword() === null){
            throw new BadTokenException('Bad JSON payload');
        }

        $registeredClient = $this->getValidUser($data->getId(), $data->getNewUserToken());

        if($registeredClient->getActive() === true){
            throw new Exception('Account already active');
        }

        //since we cleared the cache, the $data is no longer linked to the entity so we use the registered client to update
        $registeredClient->setPlainPassword($data->getPlainPassword());
        $registeredClient->setActive(true);

        //resetting the token to null as we have redefined the password
        $registeredClient->setNewUserToken(null);

        return $registeredClient;
    }

}
