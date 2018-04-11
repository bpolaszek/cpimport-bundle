<?php

namespace BenTools\CpImportBundle\Command;

use BenTools\CpImportBundle\CpImportProcessBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class CpImportRunCommand extends Command
{
    /**
     * @var CpImportProcessBuilder
     */
    private $processBuilder;

    /**
     * @inheritDoc
     */
    public function __construct(CpImportProcessBuilder $processBuilder)
    {
        parent::__construct('cpimport:run');
        $this->processBuilder = $processBuilder;
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->addArgument('database', InputArgument::REQUIRED, 'Database');
        $this->addArgument('table', InputArgument::REQUIRED, 'Table');
        $this->addArgument('file', InputArgument::REQUIRED, 'CSV File');
        $this->addOption('delimiter', null, InputOption::VALUE_OPTIONAL);
        $this->addOption('enclosure', null, InputOption::VALUE_OPTIONAL);
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output = new SymfonyStyle($input, $output);
        $options = [];
        if (null !== $input->getOption('delimiter')) {
            $options['delimiter'] = $input->getOption('delimiter');
        }
        if (null !== $input->getOption('enclosure')) {
            $options['enclosure'] = $input->getOption('enclosure');
        }
        $process = $this->processBuilder->createProcess($input->getArgument('file'), $input->getArgument('database'), $input->getArgument('table'), $options);
        $process->run(function ($type, $buffer) use ($output) {
            if (Process::ERR === $type) {
                $output->error($buffer);
            } else {
                $output->write($buffer);
            }
        });
    }
}
