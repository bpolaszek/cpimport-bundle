<?php

namespace BenTools\CpImportBundle\Watcher;

use CallbackFilterIterator;
use DirectoryIterator;
use SplObserver;

class PolledDirectory implements WatchedDirectoryInterface
{

    /**
     * @var SplObserver[]
     */
    private $observers = [];

    /**
     * @var string
     */
    private $directory;

    /**
     * @var string[]
     */
    private $files = [];

    /**
     * @var array|string[]
     */
    private $newFiles = [];

    /**
     * WatchedDirectory constructor.
     */
    public function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->synchronize(false);
    }

    /**
     * @param bool $populateNewFiles
     */
    public function synchronize(bool $populateNewFiles): void
    {
        $iterator = new CallbackFilterIterator(
            new DirectoryIterator($this->directory),
            function (DirectoryIterator $file) {
                return $file->isFile();
            }
        );

        $files = [];
        foreach ($iterator as $file) {
            $files[] = $file->getBasename();
        }

        if (true === $populateNewFiles) {
            $this->newFiles = [];
            $stored = $this->files;
            $current = $files;
            $this->newFiles = array_diff($current, $stored);
        }

        $this->files = $files;
    }


    /**
     * @inheritDoc
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * @inheritDoc
     */
    public function getNewFiles(): iterable
    {
        return $this->newFiles;
    }

    /**
     * @inheritDoc
     */
    public function attach(SplObserver $observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * @inheritDoc
     */
    public function detach(SplObserver $observer)
    {
        foreach ($this->observers as $o => $_observer) {
            if ($_observer === $observer) {
                unset($this->observers[$o]);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function notify()
    {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
}
