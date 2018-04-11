<?php

namespace BenTools\CpImportBundle\Watcher;

use SplSubject;

interface WatchedDirectoryInterface extends SplSubject
{

    /**
     * @return string
     */
    public function getDirectory(): string;

    /**
     * @return iterable
     */
    public function getNewFiles(): iterable;
}
