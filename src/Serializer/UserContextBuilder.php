<?php

namespace App\Serializer;

use ApiPlatform\Core\Exception\RuntimeException;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class UserContextBuilder
 * @package App\Serializer
 */
class UserContextBuilder implements SerializerContextBuilderInterface
{
    /**
     * @var SerializerContextBuilderInterface
     */
    private SerializerContextBuilderInterface $decorated;

    /**
     * @var AuthorizationCheckerInterface 
     */
    private AuthorizationCheckerInterface $authorizationChecker;

    /**
     * UserContextBuilder constructor.
     * @param SerializerContextBuilderInterface $decorated
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(SerializerContextBuilderInterface $decorated,
        AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->decorated = $decorated;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param Request $request
     * @param bool $normalization
     * @param array|null $extractedAttributes
     * @throws RuntimeException
     * @return array
     */
    public function createFromRequest(Request $request,
                                      bool $normalization,
                                      array $extractedAttributes = null): array
    {
        $context = $this->decorated->createFromRequest(
            $request, $normalization, $extractedAttributes
        );

        // Class being serialized/deserialized
        $resourceClass = $context['resource_class'] ?? null; // Default to null if not set

        if (User::class === $resourceClass) {
            if (isset($context['groups']) && $normalization === true) {
                if ($this->authorizationChecker->isGranted(RoleEnum::ROLE_ADMIN)) {
                    $context['groups'][] = 'get-admin';
                }
            }
         }

        return $context;
    }
}