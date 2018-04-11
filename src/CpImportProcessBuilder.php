<?php

namespace BenTools\CpImportBundle;

use Symfony\Component\Process\Process;

class CpImportProcessBuilder
{
    /**
     * @var string
     */
    private $cpImportBin;

    /**
     * CpImport constructor.
     * @param string $cpImportBin
     */
    public function __construct(string $cpImportBin)
    {
        $this->cpImportBin = $cpImportBin;
    }

    /**
     * @param string $filepath
     * @param string $db
     * @param string $table
     * @param array  $options
     * @return Process
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function createProcess(string $filepath, string $db, string $table, array $options = []): Process
    {
        if (!is_executable($this->cpImportBin)) {
            throw new \RuntimeException(sprintf('%s not found or not executable.', $this->cpImportBin));
        }
        $command = [$this->cpImportBin];

        if (!empty($options['delimiter'])) {
            $command[] = '-s';
            $command[] = $options['delimiter'];
        }

        if (!empty($options['enclosure'])) {
            $command[] = '-E';
            $command[] = $options['enclosure'];
        }

        $command[] = $db;
        $command[] = $table;
        $command[] = $filepath;

        return new Process($command);
    }
}
