<?php
// api\src\Controller\DeleteClientUserFromClientAction.php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\ClientUser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * "path"="/clients/{id}/users/{client_id}"
 * "method"="DELETE"
 *
 * Class DeleteClientUserFromClientAction
 * @package App\Controller\ClientIntegration
 */
class DeleteClientUserFromClientAction
{

    private $container;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }

    public function __invoke(Client $client, ClientUser $data)
    {

        $data->removeClient($client);

        $this->em->persist($data);
        $this->em->flush();

        return new Response(null, 204, ['message' => 'Client deletion successful']);
    }
}
