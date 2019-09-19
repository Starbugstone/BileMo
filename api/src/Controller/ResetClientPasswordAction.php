<?php

namespace App\Controller;


use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

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