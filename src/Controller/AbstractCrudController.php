<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractCrudController
 */
abstract class AbstractCrudController extends Controller
{
    /**
     * @param UserInterface $user
     * @return Response
     */
    public function list(UserInterface $user): Response
    {
        $entities = $this->getDoctrine()->getRepository($this->getEntityName())->findBy(['user' => $user]);
        return $this->render(
            sprintf('%s/list.html.twig', $this->getTemplateBasePath()),
            [
                'entities' => $entities,
            ]
        );
    }

    /**
     * @param string $action
     * @param array $options
     * @return string
     */
    protected function generateUrlForAction(string $action, array $options = []): string
    {
        return $this->generateUrl(
            sprintf('%s_%s', $this->getRoutePrefix(), $action),
            $options
        );
    }

    /**
     * @return string
     */
    abstract protected function getTemplateBasePath(): string;

    /**
     * @return string
     */
    abstract protected function getEntityName(): string;

    /**
     * @return string
     */
    abstract protected function getRoutePrefix(): string;
}
