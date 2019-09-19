<?php
// api/src/Controller/ClientIntegration/VerifyClientTokenAction.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Exception\BadTokenException;
use App\Repository\ClientRepository;
use Doctrine\Common\Persistence\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ActivateClientPasswordAction
{

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
        $this->em->clear(); //needed to clear doctrine cache else it returns the same as $data
        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->find($data->getId());

        if($registeredClient->getNewUserToken() === null){
            throw new BadTokenException('No define password key found');
        }

        if($registeredClient->getActive() === true){
            throw new Exception('Account already active');
        }

        if ($data->getNewUserToken() !== $registeredClient->getNewUserToken()) {
            throw new BadTokenException('the sent token is incorrect');
        }

        //since we cleared the cache, the $data is no longer linked to the entity so we use the registered client to update
        $registeredClient->setPlainPassword($data->getPlainPassword());
        $registeredClient->setActive(true);

        //resetting the token to null as we have redefined the password
        $registeredClient->setNewUserToken(null);

        return $registeredClient;
    }

}