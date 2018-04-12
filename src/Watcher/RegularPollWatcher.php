<?php

namespace BenTools\CpImportBundle\Watcher;

class RegularPollWatcher implements DirectoryWatcherInterface
{
    /**
     * @var int
     */
    private $pollEveryMs;

    /**
     * @var PolledDirectory[]
     */
    private $watchedDirectories = [];

    /**
     * RegularPollWatcher constructor.
     */
    public function __construct(int $pollEveryMs)
    {
        $this->pollEveryMs = $pollEveryMs;
    }

    /**
     * @inheritDoc
     */
    public function watch(string $directory): WatchedDirectoryInterface
    {
        $watchedDirectory = $this->watchedDirectories[$directory] ?? new PolledDirectory($directory);
        $this->watchedDirectories[$directory] = $watchedDirectory;
        return $watchedDirectory;
    }


    /**
     * @inheritDoc
     */
    public function wait(bool $processExistingFiles = false): void
    {
        $isFirstIteration = true;
        while (true) {
            foreach ($this->watchedDirectories as $watchedDirectory) {
                if (true === $isFirstIteration && true === $processExistingFiles) {
                    $watchedDirectory->reset();
                }

                $watchedDirectory->synchronize(true);
                $watchedDirectory->notify();
            }

            usleep($this->pollEveryMs * 1000);
            $isFirstIteration = false;
        }
    }
}
