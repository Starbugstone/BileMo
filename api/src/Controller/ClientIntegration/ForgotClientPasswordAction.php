<?php
// api/src/Controller/ClientIntegration/ForgotClientPasswordAction.php

namespace App\Controller\ClientIntegration;


use App\Entity\Client;
use App\Entity\ForgotPasswordRequest;
use App\Mail\SendMail;
use App\Repository\ClientRepository;
use App\Token\TokenGenerator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;


/**
 * "path"="/clients/password/forgot"
 *
 * id in the url
 * send Client Email in the put request
 *
 * Class ResetClientPasswordAction
 * @package App\Controller\ClientIntegration
 */
class ForgotClientPasswordAction
{
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
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(
        ClientRepository $clientRepository,
        EntityManagerInterface $em,
        SendMail $sendMail,
        TokenGenerator $tokenGenerator,
        ValidatorInterface $validator
    ) {

        $this->clientRepository = $clientRepository;
        $this->em = $em;
        $this->sendMail = $sendMail;
        $this->tokenGenerator = $tokenGenerator;
        $this->validator = $validator;
    }

    public function __invoke(ForgotPasswordRequest $data)
    {

        //verify email as the constraints in the entity haven't been fired yet
        $emailConstraint = new Assert\Email();
        $emailConstraint->message = 'Invalid email address';

        $errors = $this->validator->validate(
            $data->getEmail(),
            $emailConstraint
        );

        if (0 !== count($errors)) {
            throw new Exception($errors[0]->getMessage());
        }

        /**
         * @var Client $registeredClient
         */
        $registeredClient = $this->clientRepository->findOneBy(['email' => $data->getEmail()]);

        if ($registeredClient === null) {
            //we didn't find a client so returning fake response
            return new Response(null, 204, ['message' => 'Reset password request successful']);
        }

        //setting a new token
        $registeredClient->setNewUserToken($this->tokenGenerator->uniqueToken());

        //sending a new email
        $this->sendMail->send('Forgot Bilmo password', 'email/sendResetPassword.html.twig', $registeredClient,
            $registeredClient->getEmail());

        $this->em->persist($registeredClient);
        $this->em->flush();

        //if we are in dev, just return the necessary info rather than mess around with mails
        //INSECURE IN PROD !!!
        if ($_ENV['APP_ENV'] === 'dev' || $_ENV['APP_ENV'] === 'test') {
            $response = new Response();
            $response->setContent(json_encode([
                'new_token' => $registeredClient->getNewUserToken(),
                'id' => $registeredClient->getId(),
            ]));
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(201);
            return $response;

        }

        return new Response(null, 204, ['message' => 'Reset password request successful']);

    }

}
