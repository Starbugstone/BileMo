<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ClientLoginFormController extends AbstractController
{
    /**
     * @Route("/client_login_form", name="client_login_form")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function index(Request $request)
    {
        if ($_ENV['APP_ENV'] !== 'dev' && $_ENV['APP_ENV'] !== 'test') {
            throw new BadRequestHttpException('This is a dev only utility');
        }

        $token = '';

        $form = $this->createFormBuilder()
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $url = 'http://api/client_login';
            $httpClient = HttpClient::create();

            $response = $httpClient->request('POST', $url, [
                'json' => [
                    'username' => $data['username'],
                    'password' => $data['password'],
                ],
            ]);

            $responseContent = json_decode($response->getContent(false));
            if ($response->getStatusCode() === 200) {
                $token = 'Bearer '.$responseContent->token;
            } else {
                $token = $responseContent->message;
            }

        }

        return $this->render('client_login_form/index.html.twig', [
            'controller_name' => 'ClientLoginFormController',
            'form' => $form->createView(),
            'token' => $token,
        ]);
    }
}
