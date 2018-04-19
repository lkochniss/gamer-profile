<?php

namespace App\Command;

use App\Repository\AbstractRepository;
use App\Repository\BlogPostRepository;
use App\Repository\GameRepository;
use App\Repository\GameSessionRepository;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AddSlugToEverythingCommand
 */
class AddSlugToEverythingCommand extends ContainerAwareCommand
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var GameRepository
     */
    private $gameRepository;

    /**
     * @var GameSessionRepository
     */
    private $gameSessionRepository;

    /**
     * @var PurchaseRepository
     */
    private $purchaseRepository;

    /**
     * AddSlugToEverythingCommand constructor.
     * @param BlogPostRepository $blogPostRepository
     * @param GameRepository $gameRepository
     * @param GameSessionRepository $gameSessionRepository
     * @param PurchaseRepository $purchaseRepository
     *
     * @SuppressWarnings(PHPMD.LongVariableName)
     */
    public function __construct(
        BlogPostRepository $blogPostRepository,
        GameRepository $gameRepository,
        GameSessionRepository $gameSessionRepository,
        PurchaseRepository $purchaseRepository
    ) {
        parent::__construct();
        $this->blogPostRepository = $blogPostRepository;
        $this->gameRepository = $gameRepository;
        $this->gameSessionRepository = $gameSessionRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    protected function configure(): void
    {
        $this->setName('entities:add:slug');
        $this->setDescription('Migration Only: Adds slug to all known entity');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @SuppressWarnings("unused")
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->updateEntities($this->blogPostRepository);
        $this->updateEntities($this->gameRepository);
        $this->updateEntities($this->gameSessionRepository);
        $this->updateEntities($this->purchaseRepository);

        $output->writeln('Done');
    }

    /**
     * @param AbstractRepository $abstractRepository
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function updateEntities(AbstractRepository $abstractRepository)
    {
        $entitis = $abstractRepository->findAll();

        foreach ($entitis as $entity) {
            $entity->setModifiedAt();
            $abstractRepository->save($entity);
        }
    }
}
