<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ClientDataPersister implements DataPersisterInterface
{

    /**
     * @var EntityManagerInterface
     */
    private $manager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->manager = $manager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Is the data supported by the persister?
     */
    public function supports($data): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Client;
    }

    /**
     * Persists the data.
     * @var Client $data this is the Client waiting to be persisted to the DB
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data)
    {
        // TODO: Implement persist() method.
        if($data->getPlainPassword()){
            $encodedPassword = $this->passwordEncoder->encodePassword($data, $data->getPlainPassword());
            $data->setPassword($encodedPassword);
            $data->eraseCredentials();
        }
        $this->manager->persist($data);
        $this->manager->flush();
    }

    /**
     * Removes the data.
     */
    public function remove($data)
    {
        // TODO: Implement remove() method.
        $this->manager->remove($data);
        $this->manager->flush();
    }
}