<?php
// api\src\Controller\DeleteClientUserAction.php

namespace App\Controller;

use App\Entity\ClientUser;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * "path"="/delete_client_users/{id}"
 *
 * Class DeleteClientUserAction
 * @package App\Controller\ClientIntegration
 */
class DeleteClientUserAction
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ClientUser $data)
    {
        //Sanity check as we are dealing with deleting data
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The Security Bundle is not registered in your application.');
        }

        $loggedInClient = $this->container->get('security.token_storage')->getToken()->getUser();
        if (null === $loggedInClient){
            throw new \Exception('something went wrong, no logged in client');
        }

        $data->removeClient($loggedInClient);

        return new Response(null, 204, ['message' => 'Client deletion successful']);
    }
}
