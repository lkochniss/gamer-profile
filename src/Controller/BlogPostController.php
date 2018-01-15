<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Game;
use App\Form\Type\BlogPostType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class BlogPostController
 */
class BlogPostController extends AbstractCrudController
{
    /**
     * @param string $gameSlug
     * @return Response
     */
    public function listBlogPostsByGame(string $gameSlug): Response
    {
        $game =  $this->getDoctrine()->getRepository(Game::class)->findOneBy(['slug' => $gameSlug]);
        $entities = $this->getDoctrine()->getRepository($this->getEntityName())->findBy(['game' => $game->getId()]);
        return $this->render(
            sprintf('%s/list-frontend.html.twig', $this->getTemplateBasePath()),
            array(
                'entities' => $entities,
            )
        );
    }

    /**
     * @param string $gameSlug
     * @param string $blogPostSlug
     * @return Response
     */
    public function showBlogPostForGame(string $gameSlug, string $blogPostSlug): Response
    {
        $entity = $this->getDoctrine()->getRepository($this->getEntityName())->findOneBy(['slug' => $blogPostSlug]);
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
        return 'blog_post';
    }
}
