<?php

namespace FSi\Bundle\AdminTranslatableBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableAwareElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;

class TranslatableWorker implements Worker
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    public function mount(Element $element)
    {
        if ($element instanceof TranslatableAwareElement) {
            $element->setLocaleManager($this->localeManager);
        }
    }
}
