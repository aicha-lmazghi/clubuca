<?php
namespace App\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAuthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $params;

    public function __construct(EntityManagerInterface $em, ContainerBagInterface $params)
    {
        $this->em = $em;
        $this->params = $params;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        
    }

    public function supports(Request $request)
    {
        
    }

    public function getCredentials(Request $request)
    {
        
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        
    }

    public function supportsRememberMe()
    {
        
    }
}