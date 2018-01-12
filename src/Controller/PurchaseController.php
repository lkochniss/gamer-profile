<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\Type\PurchaseType;

/**
 * Class PurchaseController
 */
class PurchaseController extends AbstractCrudController
{
    /**
     * @return Purchase
     */
    protected function createNewEntity()
    {
        return new Purchase();
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
