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
        $docs['paths']['/clients/{id}/activate']['put']['summary'] = 'Activate the client';
        $docs['paths']['/clients/{id}/activate']['put']['description'] = 'Activate the client with a valid token and new password';
        $docs['paths']['/clients/{id}/activate']['put']['requestBody']['description'] = 'The client activation resource';
        $docs['paths']['/clients/{id}/activate']['put']['responses']['200']['description'] = 'The client has been successfully activated';

        $docs['paths']['/clients/{id}/password/reset']['put']['summary'] = 'Reset a forgotten password';
        $docs['paths']['/clients/{id}/password/reset']['put']['description'] = 'Set a new password after having demanded a password reset';
        $docs['paths']['/clients/{id}/password/reset']['put']['requestBody']['description'] = 'The reset password resource';
        $docs['paths']['/clients/{id}/password/reset']['put']['responses']['200']['description'] = 'The password has bees successfully reset';

        $docs['paths']['/clients/self/password/update']['post']['summary'] = 'Set a new password';
        $docs['paths']['/clients/self/password/update']['post']['description'] = 'Set a new password for our account';
        $docs['paths']['/clients/self/password/update']['post']['requestBody']['description'] = 'The reset my password resource';
        $docs['paths']['/clients/self/password/update']['post']['responses']['200']['description'] = 'The password has been successfully set';

        $docs['paths']['/clients/self/users/{id}']['delete']['summary'] = 'Delete users for clients';
        $docs['paths']['/clients/self/users/{id}']['delete']['description'] = 'path for the clients to delete there users';
        $docs['paths']['/clients/self/users/{id}']['delete']['requestBody']['description'] = 'Delete Client Users resource';
        $docs['paths']['/clients/self/users/{id}']['delete']['responses']['200']['description'] = 'The user has successfully been removed';

        $docs['paths']['/clients/{id}/users/{user_id}']['delete']['summary'] = 'Remove a user from a specific client';
        $docs['paths']['/clients/{id}/users/{user_id}']['delete']['description'] = 'path to delete users from a client';
        $docs['paths']['/clients/{id}/users/{user_id}']['delete']['requestBody']['description'] = 'Delete Client Users resource';
        $docs['paths']['/clients/{id}/users/{user_id}']['delete']['responses']['200']['description'] = 'The user has successfully been removed';

        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
