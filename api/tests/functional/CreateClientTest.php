<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Client;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CreateClientTest extends ApiTestCase
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

    public function testUserCreationAdmin()
    {
        $client = $this->authUser('admin', 'password');

        $response = $client->request('POST', '/clients', [
            'json' => [
                'username' => 'test1',
                'email' => 'pass1@dev.com'
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());
        $idUrl = $obj->{"@id"};

        $response = $client->request('GET', $idUrl, [
            'json' => []
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());

        $this->assertEquals('test1', $obj->username);
        $this->assertEquals('pass1@dev.com', $obj->email);
        $this->assertContains('ROLE_CLIENT', $obj->roles);
        $this->assertFalse(in_array('ROLE_ADMIN', $obj->roles));
        $this->assertFalse($obj->active);

        //Get the created user from the database. Needed to get the token to activate user
        /** @var Client $apiClient */
        $apiClient = $this->entityManager->getRepository(Client::class)->findOneBy(array('email' => $obj->email));

        $apiClientToken = $apiClient->getNewUserToken();

        //Making sure that the user can not log in yet
        $client->request('POST', '/client_login',['json'=>[
            'username'=> 'test1',
            'password'=> ''
        ]]);
        $this->assertResponseStatusCodeSame(401);

        //testing false token
        $client->request('PUT','/activate_client/'.$apiClient->getId(),[
            'json' => [
                'newUserToken' => '123456abcdefg',
	            'plainPassword' => 'shootFirst'
            ]
        ]);
        $this->assertResponseStatusCodeSame(500);

        //now with real token
        $response = $client->request('PUT','/activate_client/'.$apiClient->getId(),[
            'json' => [
                'newUserToken' => $apiClientToken,
                'plainPassword' => 'shootFirst'
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());

        //Making sure we have the same user and that he is active
        $this->assertEquals('test1', $obj->username);
        $this->assertEquals('pass1@dev.com', $obj->email);
        $this->assertContains('ROLE_CLIENT', $obj->roles);
        $this->assertFalse(in_array('ROLE_ADMIN', $obj->roles));
        $this->assertTrue($obj->active);

        //testing that we can login with the new user
        $client->request('POST', '/client_login',['json'=>[
            'username'=> 'test1',
            'password'=> 'shootFirst'
        ]]);
        $this->assertResponseIsSuccessful();
    }

    public function testUserCreationClient()
    {
        $client = $this->authUser('client1', 'password');

        $client->request('POST', '/clients', [
            'json' => [
                'username' => 'test2',
                'email' => 'pass2@dev.com'
            ]
        ]);

        //this should fail as the client cant create a client
        $this->assertResponseStatusCodeSame(403);


    }
}
