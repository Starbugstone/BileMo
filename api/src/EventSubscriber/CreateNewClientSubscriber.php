<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Client;
use App\Token\TokenGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class CreateNewClientSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    public function __construct(TokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
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
            KernelEvents::VIEW => ['onPreDeserialize', EventPriorities::PRE_WRITE],
        ];
    }

    public function onPreDeserialize(ViewEvent $event)
    {


        //we only want posts of the client
        if(!$event->getControllerResult() instanceof Client || !$event->getRequest()->attributes->get('_api_collection_operation_name') === 'post'){
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
    }
}