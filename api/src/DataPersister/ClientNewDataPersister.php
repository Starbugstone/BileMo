<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientNewDataPersister implements DataPersisterInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var Request
     */
    private $request;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder, Request $request)
    {

        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
        $this->request = $request;
    }

    /**
     * Is the data supported by the persister?
     * @param $data
     * @return bool
     */
    public function supports($data): bool
    {
        //Request::METHOD_POST
        if($data instanceof  Client && $this->request->getMethod() === Request::METHOD_POST){
            return true;
        }
        return false;
    }

    /**
     * Persists the data.
     * @var Client $data this is the Client waiting to be persisted to the DB
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data)
    {
        //TODO for #23, if new client, generate token and send email

        $this->manager->persist($data);
        $this->manager->flush();
    }

    /**
     * Removes the data. this should never happen
     */
    public function remove($data)
    {
        $this->manager->remove($data);
        $this->manager->flush();
    }
}