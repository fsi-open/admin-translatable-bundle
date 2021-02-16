<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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

    public function mount(Element $element): void
    {
        if (true === $element instanceof TranslatableAwareElement) {
            $element->setLocaleManager($this->localeManager);
        }
    }
}
