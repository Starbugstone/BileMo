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
        // TODO: Return a properly formatted json response
    }

}