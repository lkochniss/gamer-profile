<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\Type\BlogPostType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BlogPostController
 */
class BlogPostController extends AbstractCrudController
{
    /**
     * @return Response
     */
    public function listBlogPostsByGame($id): Response
    {
        $entities = $this->getDoctrine()->getRepository($this->getEntityName())->findBy(['game' => $id]);
        return $this->render(
            sprintf('%s/list-frontend.html.twig', $this->getTemplateBasePath()),
            array(
                'entities' => $entities,
            )
        );
    }

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
