<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Entity\User;
use App\Form\Type\PurchaseType;
use App\Repository\GameRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class PurchaseController
 */
class PurchaseController extends AbstractCrudController
{
    /**
     * @param Request $request
     * @param UserInterface $user
     * @return RedirectResponse|Response
     */
    public function create(Request $request, UserInterface $user)
    {
        $purchase = $this->createNewEntity($user);

        return $this->createAndHandleForm($purchase, $request, 'create');
    }

    /**
     * @param int $id
     * @param GameRepository $gameRepository
     * @param Request $request
     * @param UserInterface $user
     * @return RedirectResponse|Response
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function createForGame(int $id, GameRepository $gameRepository, Request $request, UserInterface $user)
    {
        $game = $gameRepository->find($id);
        $purchase = $this->createNewEntity($user);
        $purchase->setGame($game);

        return $this->createAndHandleForm($purchase, $request, 'create');
    }

    /**
     * @param int $id
     * @param Request $request
     * @param UserInterface $user
     * @return RedirectResponse|Response
     *
     * @SuppressWarnings(PHPMD.ShortVariableName)
     */
    public function edit(int $id, Request $request, UserInterface $user)
    {
        $purchase = $this->getDoctrine()->getRepository($this->getEntityName())->findOneBy([
            'id' => $id,
            'user' => $user
        ]);

        if (is_null($purchase)) {
            throw new NotFoundHttpException();
        }

        return $this->createAndHandleForm($purchase, $request, 'edit', ['id' => $purchase->getId()]);
    }

    /**
     * @param Purchase $purchase
     */
    protected function handleValidForm(Purchase $purchase): void
    {
        $repository = $this->getDoctrine()->getRepository($this->getEntityName());
        $repository->save($purchase);
    }

    /**
     * @param Purchase $purchase
     * @param Request $request
     * @param $action
     * @param array $options
     * @return RedirectResponse|Response
     */
    protected function createAndHandleForm(Purchase $purchase, Request $request, $action, array $options = [])
    {
        $form = $this->createForm(
            $this->getFormType(),
            $purchase,
            [
                'action' => $this->generateUrlForAction($action, $options),
                'method' => 'POST',
            ]
        );

        if (in_array($request->getMethod(), ['POST'])) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->handleValidForm($purchase);

                return $this->redirect($this->generateUrlForAction('list'));
            }
        }

        return $this->render(
            sprintf('%s/edit.html.twig', $this->getTemplateBasePath()),
            [
                'entity' => $purchase,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @param User $user
     * @return Purchase
     */
    protected function createNewEntity(User $user): Purchase
    {
        return new Purchase($user);
    }

    /**
     * @return string
     */
    protected function getFormType(): string
    {
        return PurchaseType::class;
    }

    /**
     * @return string
     */
    protected function getTemplateBasePath(): string
    {
        return 'Purchase';
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return 'App\Entity\Purchase';
    }

    /**
     * @return string
     */
    protected function getRoutePrefix(): string
    {
        return 'purchase';
    }
}
