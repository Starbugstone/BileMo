<?php
// api/src/Controller/ClientIntegration/UpdateClientPasswordAction.php

namespace App\Controller\ClientIntegration;

use Psr\Container\ContainerInterface;

/**
 * "path"="/update_my_password"
 *
 * Class UpdateClientPasswordAction
 * @package App\Controller\ClientIntegration
 */
class UpdateClientPasswordAction
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($data)
    {
        //Sanity check as we are dealing with user passwords
        if (!$this->container->has('security.token_storage')) {
            throw new \LogicException('The Security Bundle is not registered in your application.');
        }

        $loggedInClient = $this->container->get('security.token_storage')->getToken()->getUser();
        if (null === $loggedInClient){
            throw new \Exception('something went wrong, no logged in client');
        }

        $loggedInClient->setPlainPassword($data->getPlainPassword());
        //resetting the token to null as we have redefined the password
        $loggedInClient->setNewUserToken(null);
        //After password change, old tokens are still valid
        $loggedInClient->setPasswordChangeDate(time());

        return $loggedInClient;

    }
}
