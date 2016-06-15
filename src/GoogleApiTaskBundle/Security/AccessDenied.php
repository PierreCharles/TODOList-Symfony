<?php

namespace GoogleApiTaskBundle\Security;

use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class AccessDenied implements AccessDeniedHandlerInterface {
    private $client;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
        $client->getGoogleClient()->setScopes(array('https://www.googleapis.com/auth/tasks'));
    }
    

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new RedirectResponse($this->client->createAuthUrl());
    }
} 