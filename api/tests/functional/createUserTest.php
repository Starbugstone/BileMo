<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class createUserTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use userAuthTrait;

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
//        $idUrl = $obj->@id;
//        dd($obj);


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
