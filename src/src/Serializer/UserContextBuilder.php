<?php

namespace App\Serializer;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Allow the current user to access at a custom group by adding it if he's admin
 */
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

    /**
     * Provide a new group to the user to access at different resources if he's admin
     * Allows
     *
     * @param Request $request
     * @param boolean $normalization
     * @param array|null $extractedAttributes
     * 
     * @return array context
     */
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
        ) {
            // Add special group to serialization context if the current user is admin
            $context['groups'][] = 'get-admin';
            $context['groups'][] = 'delete-user';    
        }

        return $context;
    }

}
