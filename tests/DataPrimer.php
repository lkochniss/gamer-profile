<?php


namespace App\Tests;

use App\DataFixtures\AppFixtures;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpKernel\KernelInterface;

class DataPrimer
{
    /**
     * @param KernelInterface $kernel
     * @throws \Exception
     * https://www.sitepoint.com/quick-tip-testing-symfony-apps-with-a-disposable-database/
     */
    public static function setUp(KernelInterface $kernel): void
    {
        // Make sure we are in the test environment
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        // Execute migrations
        $input = new ArrayInput(array(
            'command' => 'do:da:cr'
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);

        // Get the entity manager from the service container
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');

        // Run the schema update tool using our entity metadata
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metadata);

        // If you are using the Doctrine Fixtures Bundle you could load these here
        $fixtureLoader = new AppFixtures();
        $fixtureLoader->load($entityManager);
    }

    /**
     * @param KernelInterface $kernel
     * @throws \Exception
     */
    public static function drop(KernelInterface $kernel): void
    {
        // Make sure we are in the test environment
        if ('test' !== $kernel->getEnvironment()) {
            throw new \LogicException('Primer must be executed in the test environment');
        }

        $application = new Application($kernel);
        $application->setAutoExit(false);

        // Execute migrations
        $input = new ArrayInput(array(
            'command' => 'do:da:dr',
            '--force' => true,
        ));

        $output = new BufferedOutput();
        $application->run($input, $output);
    }
}
