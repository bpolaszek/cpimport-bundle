<?php

namespace BenTools\CpImportBundle\Command;

use BenTools\CpImportBundle\CpImportFileWatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);
        $lock = $this->lockFactory->createLock(__FILE__);
        if ($lock->acquire()) {
            $this->watcher->wait();
            $lock->release();
        } else {
            $output->error(sprintf('%s is already running.', $this->getName()));
        }
    }
}
