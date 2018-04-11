<?php

namespace BenTools\CpImportBundle\Command;

use BenTools\CpImportBundle\CpImportFileWatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CpImportWatchCommand extends Command
{
    /**
     * @var CpImportFileWatcher
     */
    private $watcher;

    /**
     * @inheritDoc
     */
    public function __construct(CpImportFileWatcher $watcher)
    {
        parent::__construct('cpimport:watch');
        $this->watcher = $watcher;
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->watcher->wait();
    }
}
