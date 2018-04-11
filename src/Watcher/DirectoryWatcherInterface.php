<?php

namespace BenTools\CpImportBundle\Watcher;

interface DirectoryWatcherInterface
{
    /**
     * @param string $directory
     * @return WatchedDirectoryInterface
     */
    public function watch(string $directory): WatchedDirectoryInterface;

    /**
     * Watch for file modifications. (blocking)
     */
    public function wait(): void;
}
