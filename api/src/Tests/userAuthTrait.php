<?php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

Trait userAuthTrait
{
    public function authUser(string $user, string $pass): Client
    {
        $client = ApiTestCase::createClient();

        try {
            $response = $client->request('POST', '/client_login', [
                'json' => [
                    'username' => $user,
                    'password' => $pass
                ]
            ]);
            $obj = json_decode($response->getContent());
        } catch (TransportExceptionInterface $e) {
            echo 'Transport Exception '.$e;
            die();
        } catch (ClientExceptionInterface $e) {
            echo 'Client Exception '.$e;
            die();
        } catch (RedirectionExceptionInterface $e) {
            echo 'Redirection Exception '.$e;
            die();
        } catch (ServerExceptionInterface $e) {
            echo 'Server Exception '.$e;
            die();
        }

        return ApiTestCase::createClient([], [
            'headers' => ['Authorization' => 'bearer ' . $obj->token]
        ]);
    }
}
