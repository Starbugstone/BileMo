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

//        $customDefinition = [
//            'name' => 'fields',
//            'description' => 'Fields to remove of the output',
//            'default' => 'id',
//            'in' => 'query',
//        ];
//
//
//        // e.g. add a custom parameter
//        $docs['paths']['/foos']['get']['parameters'][] = $customDefinition;
//
//        // e.g. remove an existing parameter
//        $docs['paths']['/foos']['get']['parameters'] = array_values(array_filter($docs['paths']['/foos']['get']['parameters'], function ($param) {
//            return $param['name'] !== 'bar';
//        }));

        //Override the descriptions
        $docs['paths']['/activate_client/{id}']['put']['summary'] = 'Activate the client';
        $docs['paths']['/activate_client/{id}']['put']['description'] = 'Activate the client with a valid token and new password';
        $docs['paths']['/activate_client/{id}']['put']['requestBody']['description'] = 'The client activation resource';
        $docs['paths']['/activate_client/{id}']['put']['responses']['200']['description'] = 'The client has been successfully activated';

        // Override title
//        $docs['info']['title'] = 'My Api Foo';
//        echo "<pre>";
//        var_dump($docs);
//        echo "</pre>";
//        die();

        //Override the descriptions
//        $docs['paths']['/activate_client/{id}']['put']['summary'] = 'My Api Foo';
//        $docs['paths']['/activate_client/{id}']['put']['description'] = 'My Api BAR';


        return $docs;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $this->decorated->supportsNormalization($data, $format);
    }
}
