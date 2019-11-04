<?php
// /api/src/EventSubscriber/CreateNewClientSubscriber.php

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Client;
use App\Mail\SendMail;
use App\Token\TokenGenerator;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CreateNewClientSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;
    /**
     * @var SendMail
     */
    private $sendMail;

    public function __construct(TokenGenerator $tokenGenerator, SendMail $sendMail)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->sendMail = $sendMail;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['CreateNewClient', EventPriorities::PRE_WRITE],
        ];
    }

    /**
     * create the new client and send mail for the password creation
     * @param ViewEvent $event
     * @throws Exception
     */
    public function CreateNewClient(ViewEvent $event)
    {
        //we only want posts of the client
        if (!$event->getControllerResult() instanceof Client || $event->getRequest()->getMethod() !== Request::METHOD_POST) {
            return;
        }

        /**
         * @var Client $client
         */
        $client = $event->getControllerResult();

        //create token
        $client->setNewUserToken($this->tokenGenerator->uniqueToken());

        //TODO: do not allow the password to be sent on POST

        //send mail to client
        $mailSent = $this->sendMail->send(
            'New account created for ' . $client->getUsername(),
            'email/sendCreatePassword.html.twig',
            $client,
            $client->getEmail()
        );
        if (!$mailSent) {
            throw new Exception('mail not sent');
        }
    }
}