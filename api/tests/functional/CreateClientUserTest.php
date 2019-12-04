<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CreateClientUserTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use userAuthTrait;

    public function testUserClientCreation()
    {
        $client = $this->authUser('client1', 'password');

        //Create a new user for client1
        $response = $client->request('POST', '/users', [
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
        $response = $client->request('GET', '/users', [
            'json' => [
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());

        $this->assertTrue($this->checkIfClientIsMemberOfUser($obj->{"hydra:member"}));

    }

    public function testUserClientDelete()
    {
        $client = $this->authUser('client1', 'password');
        //Create a new user for client1
        $response = $client->request('POST', '/users', [
            'json' => [
                'email' => 'newClientUser@local.dev'
            ]
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());
        //now delete the user
        $client->request('DELETE', '/clients'.$obj->{"@id"}, [
            'json' => [
            ]
        ]);
        $this->assertResponseIsSuccessful();

        //make sure that the user no longer exists for client1
        $response = $client->request('GET', '/users', [
            'json' => [
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $obj = json_decode($response->getContent());

        $this->assertFalse($this->checkIfClientIsMemberOfUser($obj->{"hydra:member"}));
    }

    private function checkIfClientIsMemberOfUser($clientArray)
    {
        //probably a better way to check but running out of time
        $isMember = false;
        foreach ($clientArray as $clientUser) {
            if ($clientUser->email === 'newClientUser@local.dev') {
                $isMember = true;
            }
        }
        return $isMember;
    }

}
