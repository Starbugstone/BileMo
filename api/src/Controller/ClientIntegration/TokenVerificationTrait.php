<?php
// api/src/Controller/ClientIntegration/TokenVerificationTrait.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Exception\BadTokenException;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;

Trait TokenVerificationTrait
{
    /**
     * @var ClientRepository
     */
    protected $clientRepository;
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * verifies that the user is valid and
     * @param int $id the id of the user in database
     * @param string $token the token to verify
     * @return Client
     * @throws BadTokenException
     */
    public function getValidUser(int $id, string $token):Client
    {
        //clear doctrine cache else we return data and not the DB entity
        $this->em->clear();

        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->find($id);
        if ($registeredClient->getNewUserToken() === null) {
            throw new BadTokenException('No defined password key found');
        }

        if ($token !== $registeredClient->getNewUserToken()) {
            throw new BadTokenException('the sent token is incorrect');
        }

        return $registeredClient;
    }

    /**
     * @required
     * @param EntityManagerInterface $entityManager
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @required
     * @param ClientRepository $clientRepository
     */
    public function setClientRepository(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }
}
