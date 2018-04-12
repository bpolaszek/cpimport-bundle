<?php

namespace BenTools\CpImportBundle;

use BenTools\CpImportBundle\Watcher\DirectoryAction;
use BenTools\CpImportBundle\Watcher\DirectoryWatcherInterface;

class CpImportFileWatcher
{
    /**
     * @var DirectoryWatcherInterface
     */
    private $watcher;

    /**
     * @var CpImportProcessBuilder
     */
    private $processBuilder;

    /**
     * CpImportFileWatcher constructor.
     * @param DirectoryWatcherInterface $watcher
     */
    public function __construct(DirectoryWatcherInterface $watcher, CpImportProcessBuilder $processBuilder)
    {
        $this->watcher = $watcher;
        $this->processBuilder = $processBuilder;
    }

    public function registerImportAction(string $directory, string $db, string $table, array $options = []): void
    {
        $this->watcher->watch($directory)->attach(
            new DirectoryAction(
                function (string $directory, string $filename) use ($db, $table, $options) {
                    $filepath = sprintf('%s/%s', $directory, $filename);
                    if ('csv' === pathinfo($filepath, PATHINFO_EXTENSION)) {
                        $this->processBuilder->createProcess($filepath, $db, $table, $options)
                            ->run($options['callback'] ?? null);
                        unlink($filepath);
                    }
                }
            )
        );
    }

    /**
     * Wait for files.
     */
    public function wait(): void
    {
        $this->watcher->wait();
    }
}
