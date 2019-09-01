<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Client;
use App\Entity\ClientUser;
use App\Repository\ClientUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CreateClientUserSubscriber implements EventSubscriberInterface
{

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var ClientUserRepository
     */
    private $clientUserRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TokenStorageInterface $tokenStorage, ClientUserRepository $clientUserRepository, EntityManagerInterface $entityManager)
    {

        $this->tokenStorage = $tokenStorage;
        $this->clientUserRepository = $clientUserRepository;
        $this->entityManager = $entityManager;
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
        return array(
          KernelEvents::VIEW => ['getAuthenticatedClient', EventPriorities::PRE_WRITE]
//          KernelEvents::VIEW => ['getAuthenticatedClient', EventPriorities::PRE_DESERIALIZE]
//            Events::prePersist
        );
    }

//    public function prePersist(LifecycleEventArgs $args){
//        $entity = $args->getEntity();
//
//
//        if(!$entity instanceof  ClientUser){
//            return;
//        }
//
//        /**
//         * @var Client $client the logged in client
//         */
//        $client = $this->tokenStorage->getToken()->getUser();
//
//
//        $alreadyClient = $this->clientUserRepository->findOneBy(['username' => $entity->getUsername()]);
//        if($alreadyClient !== null){
//            $entity = $alreadyClient;
//        }
//
//        $entityManager = $args->getEntityManager();
//
//        $entity->addClient($client);
////        $entityManager->
//        $entityManager->remove($entity);
//        $entityManager->persist($entity);
//    }

    /**
     * Set the ClientUser client as the actual client
     * @param ViewEvent $event
     *
     */
    public function getAuthenticatedClient(ViewEvent $event)
    {
        $entity = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        /**
         * @var Client $client the logged in client
         */
        $client = $this->tokenStorage->getToken()->getUser();

        if(!$entity instanceof ClientUser || $method !== Request::METHOD_POST){
            return;
        }

        //TODO: Change the ClientUser username to Email and check for unicity
        //check if the client already exists, if yes no need to create it
        $alreadyClient = $this->clientUserRepository->findOneBy(['username' => $entity->getUsername()]);
        if($alreadyClient !== null){
            $this->entityManager->remove($entity);
            $entity = $alreadyClient;
            //TODO this does set the client to the existing user but also creates a user with no client
            //I can not dynamicly change the entity here. Only modify it so this doesn't work
//            $this->entityManager->merge($entity);
//            $this->entityManager->refresh($entity);

            $this->entityManager->persist($entity);

        }


        $entity->addClient($client);

    }

}