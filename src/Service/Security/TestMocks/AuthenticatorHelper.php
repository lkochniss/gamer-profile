<?php

namespace App\Service\Security\TestMocks;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * Class AuthenticatorHelper
 *
 * The helper needs to be within src or the service can't autowire
 */
class AuthenticatorHelper extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * CognitoAuthenticator constructor.
     * @param RouterInterface $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     */
    public function __construct(
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return 'security_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => 'lukas@kochniss.com',
            'password' => '1234',
            'csrf_token' => 'login',
        ];

        return $credentials;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return null|UserInterface
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $user = new User();
        $user->setEmail($credentials['email']);
        $user->setSteamId(76561198045607524);

        return $user;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     *
     * @return bool
     *
     * @SuppressWarnings("unused")
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse
     *
     * @SuppressWarnings("unused")
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): RedirectResponse
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)
        ) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('homepage_dashboard'));
    }

    /**
     * @return string
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('security_login');
    }
}
