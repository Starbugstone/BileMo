<?php
// api\src\Controller\VerifyClientTokenAction.php

namespace App\Controller;


use App\Entity\Client;
use App\Repository\ClientRepository;

class ActivateClientAction
{

    /**
     * @var ClientRepository
     */
    private $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {

        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Client $client
     * @return Client
     * @throws \Exception
     */
    public function __invoke(Client $data): Client
    {
        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->find($data->getId());
        if($data->getNewUserToken() !== $registeredClient->getNewUserToken()){
            throw new \Exception('bad token');
        }

        var_dump($data);
        die('updating client');

        return $client;
    }

}