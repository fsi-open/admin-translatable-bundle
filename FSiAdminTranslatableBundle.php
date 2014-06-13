<?php

namespace FSi\Bundle\AdminTranslatableBundle;

use FSi\Bundle\AdminTranslatableBundle\DependencyInjection\FSIAdminTranslatableExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class FSiAdminTranslatableBundle extends Bundle
{
    /**
     * @return FSIAdminTranslatableExtension
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FSIAdminTranslatableExtension();
        }

        return $this->extension;
    }
}
