<?php
// api\src\Controller\DeleteClientUserAction.php

namespace App\Controller;

use App\Entity\ClientUser;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * "path"="/clients/self/users/{id}"
 * "method"="DELETE"
 *
 * Class DeleteClientUserAction
 * @package App\Controller\ClientIntegration
 */
class DeleteClientUserAction
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

    public function __invoke(ClientUser $data)
    {
        //Sanity check as we are dealing with deleting data
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The Security Bundle is not registered in your application.');
        }

        $loggedInClient = $this->container->get('security.token_storage')->getToken()->getUser();
        if (null === $loggedInClient) {
            throw new \Exception('something went wrong, no logged in client');
        }

        $data->removeClient($loggedInClient);

        $this->em->persist($data);
        $this->em->flush();

        return new Response(null, 204, ['message' => 'Client deletion successful']);
    }
}
