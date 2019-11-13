<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Encode the Client password from the plain password on save to database
 *
 * Class ClientUpdateDataPersister
 * @package App\DataPersister
 */
class ClientUpdateDataPersister implements DataPersisterInterface
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
     * @param $data
     * @return bool
     */
    public function supports($data): bool
    {
        return $data instanceof Client;
    }

    /**
     * Persists the data.
     * @var Client $data this is the Client waiting to be persisted to the DB
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data)
    {
        //TODO Check if constraints work here or are we before the constraint verification (normaly no as the object is already hydrated
        if($data->getPlainPassword()){
            $encodedPassword = $this->passwordEncoder->encodePassword($data, $data->getPlainPassword());
            $data->setPassword($encodedPassword);
            $data->eraseCredentials();
        }
        $this->manager->persist($data);
        $this->manager->flush();

        return $data;
    }

    /**
     * Removes the data.
     * @var Client $data
     */
    public function remove($data)
    {
        $this->manager->remove($data);
        $this->manager->flush();
    }
}
