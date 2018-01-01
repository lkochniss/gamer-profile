<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class BlogPostRepository
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @param BlogPost $blogPost
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(BlogPost $blogPost): void
    {
        $this->getEntityManager()->persist($blogPost);
        $this->getEntityManager()->flush($blogPost);
    }
}
