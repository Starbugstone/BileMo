<?php
// api/src/Serializer/AdminGroupsContextBuilder.php

namespace App\Serializer;

use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\ClientUser;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Client;

class AdminGroupsContextBuilder implements SerializerContextBuilderInterface
{
    private $decorated;
    private $authorizationChecker;

    public function __construct(
        SerializerContextBuilderInterface $decorated,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function createFromRequest(Request $request, bool $normalization, ?array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest($request, $normalization, $extractedAttributes);
        $resourceClass = $context['resource_class'] ?? null;

        $isAdmin = $this->authorizationChecker->isGranted('ROLE_ADMIN');

        if ($resourceClass === Client::class && isset($context['groups']) && $isAdmin) {
            $context['groups'][] = $normalization ? 'admin_client_read' : 'admin_client_write';
        }

        if ($resourceClass === ClientUser::class && isset($context['groups']) && $isAdmin) {
            $context['groups'][] = $normalization ? 'admin_user_read' : 'admin_user_write';
        }

        return $context;
    }

}
