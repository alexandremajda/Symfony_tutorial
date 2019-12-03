<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


class UserContextBuilder implements SerializerContextBuilderInterface
{
    private $decorator;
    private $authorizationChecker;

    public function __construct(
        SerializerContextBuilderInterface $decorator, 
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->decorator = $decorator;
        $this->authorizationChecker = $authorizationChecker;
    }
    public function createFromRequest(
        Request $request, 
        bool $normalization, 
        ?array $extractedAttributes = null): array
    {
        $context = $this->decorator->createFromRequest($request, $normalization, $extractedAttributes);

        // The class which will be deserialized/serialized
        $resourceClass = $context['resource_class'] ?? null; // Default null if not set

        if (
            User::class === $resourceClass &&
            isset($context['groups']) &&
            $normalization &&
            $this->authorizationChecker->isGranted(User::ROLE_ADMIN)
        )
            // Add special group to serialization context if the current user is admin
            $context['groups'][] = 'get-admin';

        return $context;
    }

}
