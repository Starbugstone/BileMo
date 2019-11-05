<?php


namespace App\Tests\Entity;


use App\Entity\Client;
use App\Entity\ClientUser;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class ClientUserTest extends TestCase
{
    /**
     * @var Client $client
     */
    private $client;

    /**
     * @var ClientUser $clientUser
     */
    private $clientUser;

    protected function setUp():void
    {
        $this->client = new Client();
        $this->clientUser = new ClientUser();
    }

    protected function tearDown():void
    {
        $this->client = null;
        $this->clientUser = null;
    }

    public function testClientAttribute()
    {
        $this->assertClassHasAttribute('client', ClientUser::class);
        $this->assertClassHasAttribute('clientUsers', Client::class);
    }

    public function testIsUserOfClient()
    {
        $this->client->addClientUser($this->clientUser);
        $this->assertTrue($this->clientUser->isUserOf($this->client));
    }

    public function testIsNotUserOfClient()
    {
        $client2 = new Client();
        $client2->addClientUser($this->clientUser);
        $this->assertFalse($this->clientUser->isUserOf($this->client));
    }

    public function testIsUserOfMultipleClients()
    {
        $client2 = new Client();
        $client2->addClientUser($this->clientUser);
        $this->client->addClientUser($this->clientUser);
        $this->assertTrue($this->clientUser->isUserOf($this->client));
        $this->assertTrue($this->clientUser->isUserOf($client2));
    }
}
