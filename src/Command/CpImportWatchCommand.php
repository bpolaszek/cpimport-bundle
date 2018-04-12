<?php

namespace BenTools\CpImportBundle\Command;

use BenTools\CpImportBundle\CpImportFileWatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Lock\Factory;

class CpImportWatchCommand extends Command
{
    /**
     * @var CpImportFileWatcher
     */
    private $watcher;

    /**
     * @var Factory
     */
    private $lockFactory;

    /**
     * @inheritDoc
     */
    public function __construct(CpImportFileWatcher $watcher, Factory $lockFactory)
    {
        parent::__construct('cpimport:watch');
        $this->watcher = $watcher;
        $this->lockFactory = $lockFactory;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->addOption('unlock', null, InputOption::VALUE_NONE, 'Unlocks the command if lock.');
        $this->addOption('process-existing-files', null, InputOption::VALUE_NONE, 'Process existing files.');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);
        $lock = $this->lockFactory->createLock(__FILE__, null);

        if (true === $input->getOption('unlock')) {
            $lock->release();
            $output->success(sprintf('%s was successfully unlocked.', $this->getName()));
            return;
        }

        $processExistingFiles = $input->getOption('process-existing-files');

        if ($lock->acquire()) {
            $this->watcher->wait($processExistingFiles);
            $lock->release();
        } else {
            $output->error(sprintf('%s is already running.', $this->getName()));
        }
    }
}
