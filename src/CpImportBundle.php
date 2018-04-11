<?php

namespace BenTools\CpImportBundle;

use BenTools\CpImportBundle\DependencyInjection\CpImportExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CpImportBundle extends Bundle
{

    /**
     * @inheritDoc
     */
    public function getContainerExtension()
    {
        return new CpImportExtension();
    }
}
