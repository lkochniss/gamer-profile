<?php

namespace App\Controller;

use App\Entity\AbstractEntity;
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class AbstractCrudController
 */
abstract class AbstractCrudController extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request)
    {
        $entity = $this->createNewEntity();

        return $this->createAndHandleForm($entity, $request, 'create');
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createForGame(int $id, GameRepository $gameRepository, Request $request)
    {
        $game = $gameRepository->find($id);
        $entity = $this->createNewEntity();
        $entity->setGame($game);

        return $this->createAndHandleForm($entity, $request, 'create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function edit(int $id, Request $request)
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->find($id);
        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }
        return $this->createAndHandleForm($entity, $request, 'edit', ['id' => $entity->getId()]);
    }

    /**
     * @param string $slug
     * @return Response
     */
    public function show(string $slug): Response
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->findOneBy(['slug' => $slug]);
        if (is_null($entity)) {
            throw new NotFoundHttpException();
        }

        return $this->render(#
            sprintf('%s/show.html.twig', $this->getTemplateBasePath()),
            [
                'entity' => $entity
            ]
        );
    }

    /**
     * @return Response
     */
    public function listFrontend(): Response
    {
        $entities = $this->getDoctrine()->getRepository($this->getEntityName())->findAll();

        return $this->render(
            sprintf('%s/list-frontend.html.twig', $this->getTemplateBasePath()),
            [
                'entities' => $entities,
            ]
        );
    }

    /**
     * @return Response
     */
    public function listBackend(): Response
    {
        $entities = $this->getDoctrine()->getRepository($this->getEntityName())->findAll();
        return $this->render(
            sprintf('%s/list-backend.html.twig', $this->getTemplateBasePath()),
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
    protected function generateUrlForAction(string $action, array $options = array()): string
    {
        return $this->generateUrl(
            sprintf('%s_%s', $this->getRoutePrefix(), $action),
            $options
        );
    }

    /**
     * @param AbstractEntity $entity
     */
    protected function handleValidForm(AbstractEntity $entity): void
    {
        $repository = $this->getDoctrine()->getRepository($this->getEntityName());
        $repository->save($entity);
    }

    /**
     * @param AbstractEntity $entity
     * @param Request $request
     * @param $action
     * @param array $options
     * @return RedirectResponse|Response
     */
    protected function createAndHandleForm(AbstractEntity $entity, Request $request, $action, array $options = array())
    {
        $form = $this->createForm(
            $this->getFormType(),
            $entity,
            [
                'action' => $this->generateUrlForAction($action, $options),
                'method' => 'POST',
            ]
        );

        if (in_array($request->getMethod(), ['POST'])) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->handleValidForm($entity);

                return $this->redirect($this->generateUrlForAction('list'));
            }
        }

        return $this->render(
            sprintf('%s/edit.html.twig', $this->getTemplateBasePath()),
            [
                'entity' => $entity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @return AbstractEntity
     */
    abstract protected function createNewEntity();

    /**
     * @return string
     */
    abstract protected function getFormType(): string;

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
