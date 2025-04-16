<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;  // Ajoute cette ligne
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;

class YesAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');
        
        if (strpos($authHeader, 'Bearer ') === 0) {
            $token = substr($authHeader, 7);
            $user = $this->getUserFromToken($token);  // Implémenter la méthode getUserFromToken()
            return new Passport($user);
        }

        throw new AuthenticationException('Jeton d\'authentification manquant ou invalide.');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse('/accueil');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response('Échec de l\'authentification : ' . $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }

    // Optionnel : gérer les accès anonymes
    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse('/login');
    }
}
