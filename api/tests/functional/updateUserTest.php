<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class updateUserTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use userAuthTrait;

    public function testUpdateClient1()
    {
        $client = $this->authUser('client1', 'password');

        //get our ID
        $response = $client->request('PUT', '/clients', [
            'json' => []
        ]);

        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());
        $idUrl = $obj->{"@id"};



    }
}
