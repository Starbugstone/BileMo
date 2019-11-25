<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Client;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class updateUserTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use userAuthTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp():void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUpdateClient1()
    {
        $client = $this->authUser('client1', 'password');

        //make sure we are logged in properly
        $client->request('GET', '/clients', [
            'json' => []
        ]);
        $this->assertResponseIsSuccessful();

        /** @var Client $apiClient */
        $apiClient = $this->entityManager->getRepository(Client::class)->findOneBy(array('username' => 'client1'));

        $apiClientId = $apiClient->getId();

//        $obj = json_decode($response->getContent());
//        $idUrl = $obj->{"@id"};
//        dd($obj);



    }
}
