<?php

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

interface TranslatableAwareInterface
{
    public function setLocaleManager(LocaleManager $localeManager);

    public function getLocale();
}
