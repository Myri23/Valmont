<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginFormAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // Changement ici pour utiliser le login au lieu de l'email
        $login = $request->request->get('login', '');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $login);

        return new Passport(
            new UserBadge($login, function ($userIdentifier) {
                $userRepository = $this->parameterBag->get('doctrine')->getRepository(\App\Entity\Utilisateur::class);
                $user = $userRepository->findOneBy(['login' => $userIdentifier]);

                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Identifiants invalides.');
                }

                // Vérifier si le compte a été rejeté par l'administrateur
                if ($user->getStatutVerification() === 'rejete') {
                    throw new CustomUserMessageAuthenticationException('Votre inscription a été refusée par l\'administration. Pour plus d\'informations, veuillez contacter le support.');
                }

                // Vérifier si le compte est en attente de vérification
                if ($user->getStatutVerification() === 'en_attente') {
                    throw new CustomUserMessageAuthenticationException('Votre compte est en attente de vérification par l\'administration. Vous recevrez un email lorsque votre compte sera validé.');
                }

                // Vérifier si le compte a été confirmé par email
                if (!$user->isConfirmed()) {
                    throw new CustomUserMessageAuthenticationException('Votre compte n\'est pas confirmé. Veuillez vérifier votre email pour le lien de confirmation.');
                }

                return $user;
            }),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Redirection vers la page d'accueil après connexion réussie
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}