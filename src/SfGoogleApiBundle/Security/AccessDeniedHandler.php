<?php

namespace SfGoogleApiBundle\Security\Authorization;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use HappyR\Google\ApiBundle\Services\GoogleClient;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $client;

    public function __construct(GoogleClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new RedirectResponse($this->client->generateAuthUrl());
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($event->getException() instanceof \Google_Auth_Exception ||
            $event->getException() instanceof \Google_Service_Exception
        ) {
            $event->setResponse(
                new RedirectResponse($this->client->generateAuthUrl())
            );
        }
    }
}