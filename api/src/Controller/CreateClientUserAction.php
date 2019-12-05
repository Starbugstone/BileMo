<?php
// api\src\Controller\CreateClientUserAction.php

namespace App\Controller;


use App\Entity\Client;
use App\Entity\ClientUser;
use App\Repository\ClientUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 *
 * Called when a client create a new user.
 * Since we have a many to many relation and the client can no see other users of other clients, we will check internally if the client exists
 * if the user does exist then we will not recreate it but associate the logged in client to the user.
 *
 * Called by the entity user thanks to API platform and annotations
 *
 * Class CreateClientUserAction
 * @package App\Controller
 */
class CreateClientUserAction
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var ClientUserRepository
     */
    private $clientUserRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        ClientUserRepository $clientUserRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->clientUserRepository = $clientUserRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param ClientUser $data
     * @return ClientUser
     */
    public function __invoke(ClientUser $data): ClientUser
    {
        //checking if the ClientUser already exists, if so we don't create a new one but add the client to the existing one
        $alreadyClient = $this->clientUserRepository->findOneBy(['email' => $data->getEmail()]);
        if ($alreadyClient !== null) {
            $data = $alreadyClient;
        }

        /**
         * @var Client $client the logged in client
         */
        $client = $this->tokenStorage->getToken()->getUser();

        $data->addClient($client);
        $this->entityManager->persist($data);
        $this->entityManager->flush();

        return $data;
    }

}
