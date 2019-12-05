<?php


namespace App\Tests\functional;


use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Client;
use App\Tests\userAuthTrait;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class ClientForgotPasswordTest extends ApiTestCase
{
    use ReloadDatabaseTrait;
    use userAuthTrait;

    public function testForgotPassword()
    {

        $client = self::createClient();
        $kernel = self::bootKernel();

        /**
         * @var $entityManager \Doctrine\ORM\EntityManager
         */
        $entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        //getting the client1 client from database
        /** @var Client $apiClient */
        $apiClient = $entityManager->getRepository(Client::class)->findOneBy(array('username' => 'client1'));

        //Make sure we get a valid response even when the email is wrong to avoid exposing our client emails
        $client->request('POST', '/clients/password/forgot', ['json' => [
            'email' => 'thisisabademail@nonexistant.com'
        ]]);
        $this->assertResponseIsSuccessful();

        //send the forgot password request
        $client->request('POST', '/clients/password/forgot', ['json' => [
            'email' => $apiClient->getEmail()
        ]]);
        $this->assertResponseIsSuccessful();

        //regetting the client1 to grab the token
        $entityManager->clear();
        $apiClient = $entityManager->getRepository(Client::class)->find($apiClient->getId());
        $this->assertNotNull($apiClient->getNewUserToken(), 'The new user token was not generated');

        //test that we get a bad response when sending wrong token
        $client->request('PUT', '/clients/'.$apiClient->getId().'/password/reset', [
            'json' => [
                'newUserToken' => '123456789',
                'plainPassword' => "KyloIsANub"
            ]
        ]);
        $this->assertResponseStatusCodeSame(400);

        //Reset the password with the good token
        $client->request('PUT', '/clients/'.$apiClient->getId().'/password/reset', [
            'json' => [
                'newUserToken' => $apiClient->getNewUserToken(),
                'plainPassword' => "KyloIsANub"
            ]
        ]);
        $this->assertResponseIsSuccessful();

        //try to log in with the new password
        $response = $client->request('POST', '/clients/login', ['json' => [
            'username' => 'client1',
            'password' => 'KyloIsANub'
        ]]);
        $this->assertResponseIsSuccessful();
    }
}
