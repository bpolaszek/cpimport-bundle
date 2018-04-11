<?php

namespace BenTools\CpImportBundle\Watcher;

use SplObserver;
use SplSubject;

class DirectoryAction implements SplObserver
{
    /**
     * @var callable
     */
    private $action;

    /**
     * DirectoryAction constructor.
     */
    public function __construct(callable $action)
    {
        $this->action = $action;
    }

    /**
     * @param WatchedDirectoryInterface $subject
     */
    public function update(SplSubject $watchedDirectory)
    {
        if (!$watchedDirectory instanceof WatchedDirectoryInterface) {
            throw new \InvalidArgumentException(sprintf('Expected instance of %s, got %s', WatchedDirectoryInterface::class, get_class($watchedDirectory)));
        }

        $action = $this->action;

        foreach ($watchedDirectory->getNewFiles() as $file) {
            $action($watchedDirectory->getDirectory(), $file);
        }
    }
}
