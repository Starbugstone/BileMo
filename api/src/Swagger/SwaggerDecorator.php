<?php
// api/src/Swagger/SwaggerDecorator.php

namespace App\Swagger;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class SwaggerDecorator implements NormalizerInterface
{
    private $decorated;

    public function __construct(NormalizerInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $docs = $this->decorated->normalize($object, $format, $context);

        //Override the descriptions
        $docs['paths']['/activate_client/{id}']['put']['summary'] = 'Activate the client';
        $docs['paths']['/activate_client/{id}']['put']['description'] = 'Activate the client with a valid token and new password';
        $docs['paths']['/activate_client/{id}']['put']['requestBody']['description'] = 'The client activation resource';
        $docs['paths']['/activate_client/{id}']['put']['responses']['200']['description'] = 'The client has been successfully activated';

        $docs['paths']['/reset_client_password/{id}']['put']['summary'] = 'Reset a forgotten password';
        $docs['paths']['/reset_client_password/{id}']['put']['description'] = 'Set a new password after having demanded a password reset';
        $docs['paths']['/reset_client_password/{id}']['put']['requestBody']['description'] = 'The reset password resource';
        $docs['paths']['/reset_client_password/{id}']['put']['responses']['200']['description'] = 'The password has bees successfully reset';

        $docs['paths']['/update_my_password']['post']['summary'] = 'Set a new password';
        $docs['paths']['/update_my_password']['post']['description'] = 'Set a new password for our account';
        $docs['paths']['/update_my_password']['post']['requestBody']['description'] = 'The reset my password resource';
        $docs['paths']['/update_my_password']['post']['responses']['200']['description'] = 'The password has been successfully set';

        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
