<?php

namespace App\Controller;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use Symfony\Component\Form\AbstractType;

/**
 * Class BlogPostController
 */
class BlogPostController extends AbstractCrudController
{

    /**
     * @return BlogPost
     */
    protected function createNewEntity()
    {
        return new BlogPost();
    }

    /**
     * @return string
     */
    protected function getFormType(): string
    {
        return BlogPostType::class;
    }

    /**
     * @return string
     */
    protected function getTemplateBasePath(): string
    {
        return 'BlogPost';
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return 'App\Entity\BlogPost';
    }

    /**
     * @return string
     */
    protected function getRoutePrefix(): string
    {
        return 'blog';
    }
}
