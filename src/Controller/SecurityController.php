<?php

namespace App\Controller;

use App\Form\PasswordRecoveryFormType;
use App\Form\RegistrationFormType;
use App\Service\Security\AwsCognitoClient;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

const INVALID_PASSWORD = 'InvalidPasswordException';

/**
 * Class SecurityController
 */
class SecurityController extends AbstractController
{

    /**
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render(
            'Security/login.html.twig',
            [
                'last_username' => $lastUsername,
                'error' => $error,
            ]
        );
    }

    /**
     * @param AwsCognitoClient $awsCognitoClient
     * @param Request $request
     *
     * @return Response
     */
    public function passwordRecovery(AwsCognitoClient $awsCognitoClient, Request $request): Response
    {

        $form = $this->createForm(PasswordRecoveryFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $awsCognitoClient->forgotPassword(
                    $form->get('email')->getData()
                );
            } catch (CognitoIdentityProviderException $e) {
            }
        }

        return $this->render(
            'Security/password_recovery.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @param AwsCognitoClient $awsCognitoClient
     * @param TranslatorInterface $translator
     *
     * @return Response
     */
    public function registration(
        Request $request,
        AuthenticationUtils $authenticationUtils,
        AwsCognitoClient $awsCognitoClient,
        TranslatorInterface $translator
    ): Response {
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('inviteCode')->getData() !== getenv('INVITE_CODE')) {
                $form->get('inviteCode')->addError(
                    new FormError($translator->trans('invite_code_invalid'))
                );

                return $this->render(
                    'Security/registration.html.twig',
                    [
                        'form' => $form->createView(),
                        'last_username' => $lastUsername,
                    ]
                );
            }

            try {
                $awsCognitoClient->signUp(
                    $form->get('email')->getData(),
                    $form->get('password')->getData()
                );

                return $this->redirectToRoute('security_login');
            } catch (CognitoIdentityProviderException $e) {
                switch ($e->getAwsErrorCode()) {
                    case INVALID_PASSWORD:
                        $form->get('password')->addError(
                            new FormError($translator->trans('password_invalid'))
                        );
                        break;
                    default:
                        $form->get('email')->addError(
                            new FormError($translator->trans('email_invalid'))
                        );
                        break;
                }
            } catch (\InvalidArgumentException $e) {
                $form->get('password')->addError(
                    new FormError($translator->trans('password_invalid'))
                );
            }
        }

        return $this->render(
            'Security/registration.html.twig',
            [
                'form' => $form->createView(),
                'last_username' => $lastUsername,
            ]
        );
    }
}
