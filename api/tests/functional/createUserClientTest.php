<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class createUserClientTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use userAuthTrait;

    public function testUserClientCreation()
    {
        $client = $this->authUser('client1', 'password');

        //Create a new user for client1
        $response = $client->request('POST', '/client_users', [
            'json' => [
                'email' => 'newClientUser@local.dev'
            ]
        ]);
        $this->assertResponseIsSuccessful();

        //check if the new client is created
        $obj = json_decode($response->getContent());
        $client->request('GET', $obj->{"@id"}, [
            'json' => [
                'email' => 'newClientUser@local.dev'
            ]
        ]);
        $this->assertResponseIsSuccessful();

        //make sure the user is associated to client
        $response = $client->request('GET', '/client_users', [
            'json' => [

            ]
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());

        //probably a better way to check this but running short on time
        $createdClient = false;
        foreach ($obj->{"hydra:member"} as $clientUser){
            if ($clientUser->email === 'newClientUser@local.dev'){
                $createdClient = true;
            }
        }
        $this->assertTrue($createdClient);

    }

}
