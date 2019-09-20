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
 * id in the url
 * send reset token
 * send new password
 *
 * Class ResetClientPasswordAction
 * @package App\Controller\ClientIntegration
 */
class ResetClientPasswordAction
{

    //TODO: Document in swagger / (hail) hydra format

    /**
     * @var ClientRepository
     */
    private $clientRepository;
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $em)
    {

        $this->clientRepository = $clientRepository;
        $this->em = $em;
    }

    public function __invoke(Client $data)
    {

        $this->em->clear(); //needed to clear doctrine cache else it returns the same as $data
        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->find($data->getId());
        if ($registeredClient->getNewUserToken() === null) {
            throw new BadTokenException('No define password key found');
        }

        if ($registeredClient->getActive() === false) {
            throw new Exception('Account already active');
        }

        if ($data->getNewUserToken() !== $registeredClient->getNewUserToken()) {
            throw new BadTokenException('the sent token is incorrect');
        }

        //since we cleared the cache, the $data is no longer linked to the entity so we use the registered client to update
        $registeredClient->setPlainPassword($data->getPlainPassword());

        //resetting the token to null as we have redefined the password
        $registeredClient->setNewUserToken(null);

        return $registeredClient;

    }

    /**
     * @param string $email
     * @Route("/reset_client_password/{email}", name="reset_client_password")
     */
    public function resetPassword(string $email)
    {
        dd($email);

        //TODO: check if email is in base, DO NOT SEND ERROR IF NOT

        //TODO: add token to client

        //TODO: send email to client with new token for reset


        // TODO: Return a properly formatted json response

        //TODO: No, we can use the validation groups and verify all here. The path is just /users/ID/reset. All the rest is passed via json
        //TODO: make sure we are all open. This will be used for the new password and the reset password.
    }

}