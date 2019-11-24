<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
        $idUrl = $obj->{"@id"};

        $response = $client->request('GET', $idUrl,[
            'json'=>[]
        ]);
        $this->assertResponseIsSuccessful();

        $obj = json_decode($response->getContent());

        $this->assertEquals('test1',$obj->username);
        $this->assertEquals('pass1@dev.com',$obj->email);
        $this->assertContains('ROLE_CLIENT',$obj->roles);
        $this->assertFalse(in_array('ROLE_ADMIN', $obj->roles));
        $this->assertFalse($obj->active);

        

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
