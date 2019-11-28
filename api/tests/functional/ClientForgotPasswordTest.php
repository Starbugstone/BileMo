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

        //send the forgot password request
        $client->request('POST', '/forgot_password', ['json' => [
            'email' => $apiClient->getEmail()
        ]]);
        $this->assertResponseIsSuccessful();

        //regetting the client1 to grab the token
        $entityManager->clear();
        $apiClient = $entityManager->getRepository(Client::class)->find($apiClient->getId());
        $this->assertNotNull($apiClient->getNewUserToken(), 'The new user token was not generated');
//        dd($apiClient);

        $client->request('PUT', '/reset_client_password/' . $apiClient->getId(), [
            'json' => [
                'newUserToken' => $apiClient->getNewUserToken(),
	            'plainPassword' => "KyloIsANub"
            ]
        ]);
        $this->assertResponseIsSuccessful();

        //try to log in with the new password
        $response = $client->request('POST', '/client_login',['json'=>[
            'username'=> 'client1',
            'password'=> 'KyloIsANub'
        ]]);
        $this->assertResponseIsSuccessful();


    }
}
