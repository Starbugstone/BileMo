<?php
// api\src\Controller\DeleteClientUserFromClientAction.php

namespace App\Controller;

use App\Entity\Client;
use App\Repository\ClientUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * "path"="/clients/{id}/users/{user_id}"
 * "method"="DELETE"
 *
 * Class DeleteClientUserFromClientAction
 * @package App\Controller\ClientIntegration
 */
class DeleteClientUserFromClientAction
{

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var ClientUserRepository
     */
    private $clientUserRepository;

    public function __construct(EntityManagerInterface $em, ClientUserRepository $clientUserRepository)
    {
        $this->em = $em;
        $this->clientUserRepository = $clientUserRepository;
    }

    public function __invoke( Client $data, $user_id)
    {
        $user = $this->clientUserRepository->find($user_id);

        $data->removeClientUser($user);

        $this->em->persist($data);
        $this->em->flush();

        return new Response(null, 204, ['message' => 'Client deletion successful']);
    }
}
