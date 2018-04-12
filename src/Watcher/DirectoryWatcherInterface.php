<?php

namespace BenTools\CpImportBundle\Watcher;

interface DirectoryWatcherInterface
{
    /**
     * Configure this directory to be watched.
     *
     * @param string $directory
     * @return WatchedDirectoryInterface
     */
    public function watch(string $directory): WatchedDirectoryInterface;

    /**
     * Watch for file modifications. (blocking)
     *
     * @param bool $processExistingFiles
     */
    public function wait(bool $processExistingFiles = false): void;
}
