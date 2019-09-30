<?php
// api/src/Controller/ClientIntegration/ForgotClientPasswordAction.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Exception\BadTokenException;
use App\Mail\SendMail;
use App\Repository\ClientRepository;
use App\Token\TokenGenerator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\Annotation\Route;


/**
 * "path"="/forgot_client/{id}"
 *
 * id in the url
 * send Client Email in the put request
 *
 * Class ResetClientPasswordAction
 * @package App\Controller\ClientIntegration
 */
class ForgotClientPasswordAction
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
    /**
     * @var SendMail
     */
    private $sendMail;
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(ClientRepository $clientRepository, EntityManagerInterface $em, SendMail $sendMail, TokenGenerator $tokenGenerator)
    {

        $this->clientRepository = $clientRepository;
        $this->em = $em;
        $this->sendMail = $sendMail;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function __invoke(Client $data)
    {
//        var_dump($data);
        $this->em->clear(); //needed to clear doctrine cache else it returns the same as $data
        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->find($data->getId());

        //TODO: prehaps not use the ID and just use the email to check if user exists.
        if ($registeredClient->getEmail() !== $data->getEmail()) {
            throw new Exception('Bad Email'); //TODO: make this better than a simple error
        }

        //setting a new token
        $registeredClient->setNewUserToken($this->tokenGenerator->uniqueToken());

        //sending a new email
        $this->sendMail->send('Forgot Bilmo password','email/sendResetPassword.html.twig',$registeredClient,$registeredClient->getEmail());

        //TODO: return a validation message rather than the actual data
        return $registeredClient;

    }

}
