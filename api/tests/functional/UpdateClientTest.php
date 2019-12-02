<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Client;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class UpdateClientTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use userAuthTrait;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
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

        //Changing the client email
        $response = $client->request('PUT', '/clients/' . $apiClientId, [
            'json' => [
                'email' => 'newEmail@local.dev'
            ]
        ]);

        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());
        $this->assertEquals('newEmail@local.dev', $obj->email);

        //Changing the password
        //check if constraints work
        $client->request('POST', '/update_my_password', [
            'json' => [
                'plainPassword' => '123'
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);

        //make sure we are still logged in
        $client->request('GET', '/clients', [
            'json' => []
        ]);
        $this->assertResponseIsSuccessful();

        $client->request('POST', '/update_my_password', [
            'json' => [
                'plainPassword' => '123456'
            ]
        ]);
        $this->assertResponseIsSuccessful();

        //make sure we are not logged in
        $client->request('GET', '/clients', [
            'json' => []
        ]);
        $this->assertResponseStatusCodeSame(401);

        //log back in with new password
        $client = $this->authUser('client1', '123456');
        //make sure we are logged in properly
        $client->request('GET', '/clients', [
            'json' => []
        ]);
        $this->assertResponseIsSuccessful();

    }
}
