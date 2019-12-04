<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;

class UserAuthenticationTest extends ApiTestCase
{
    use RefreshDatabaseTrait;

    public function testAuthentication()
    {
        $client = self::createClient();

        //making sure that a bad login is 401
        $client->request('POST', '/clients/login',['json'=>[
            'username'=> 'NonExistant',
            'password'=> 'ThisisNotA_Pa$$w0rD'
        ]]);
        $this->assertResponseStatusCodeSame(401);

        //This should be OK
        $response = $client->request('POST', '/clients/login',['json'=>[
            'username'=> 'client1',
	        'password'=> 'password'
        ]]);
        $this->assertResponseIsSuccessful();

        //test that we are not allowed a page without login
        $client->request('GET', '/clients',[
            'json'=>[]
        ]);
        $this->assertResponseStatusCodeSame(401);

        //test that we can get a page
        $obj = json_decode($response->getContent());
        $client->request('GET', '/clients',[
            'headers' => ['Authorization'=>'bearer '.$obj->token],
            'json'=>[]
        ]);
        $this->assertResponseIsSuccessful();
    }
}
