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
 * id in the url
 * send Client Email
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

        $this->em->clear(); //needed to clear doctrine cache else it returns the same as $data
        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->find($data->getId());

        if ($registeredClient->getEmail() !== $data->getEmail()) {
            $fake = new Client();
            return $fake; //returning fake client for security reasons to not give pirates any false info. they already tried with a true ID
            //we can turn this into a honeypot if needed
        }

        //setting a new token
        $registeredClient->setNewUserToken($this->tokenGenerator->uniqueToken());

        //sending a new email
        $this->sendMail->send('Forgot Bilmo password','email/sendResetPassword',$registeredClient,$registeredClient->getEmail());

        //TODO: return a validation message rather than the actual data
        return $registeredClient;
        
    }

}