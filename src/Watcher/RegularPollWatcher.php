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
    public function wait(): void
    {
        while (true) {
            foreach ($this->watchedDirectories as $watchedDirectory) {
                $watchedDirectory->synchronize(true);
                $watchedDirectory->notify();
            }

            usleep($this->pollEveryMs * 1000);
        }
    }
}
