<?php

namespace AgileKernelBundle\Command;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class FixturesLoadCommand
 */
class FixturesLoadCommand extends ContainerAwareCommand
{
    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('mw:fixtures:load')
            ->setDescription('Load data fixtures')
            ->addOption(
                '--force',
                '-f',
                InputOption::VALUE_NONE,
                'If set purge database before load fixtures'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('force')) {
            /** @var EntityManager $entityManager */
            $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
            $purger        = new ORMPurger($entityManager);
            /** @var Connection $connection */
            $connection = $entityManager->getConnection();
            $connection->executeQuery('SET foreign_key_checks = 0');
            $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
            $executor = new ORMExecutor($entityManager, $purger);
            $executor->execute([]);
        }

        $input   = new ArrayInput([
            'command'          => 'doctrine:fixtures:load',
            '--no-interaction' => null,
        ]);
        $command = $this->getApplication();
        $command->run($input, $output);
    }
}
