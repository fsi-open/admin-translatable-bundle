<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableCRUDElement;
use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use PhpSpec\ObjectBehavior;

class TranslatableWorkerSpec extends ObjectBehavior
{
    function let(LocaleManager $localeManager)
    {
        $this->beConstructedWith($localeManager);
    }

    function it_is_worker()
    {
        $this->shouldImplement(Worker::class);
    }

    function it_mounts_to_translatable_element(TranslatableCRUDElement $element, LocaleManager $localeManager)
    {
        $element->setLocaleManager($localeManager)->shouldBeCalled();

        $this->mount($element);
    }
}
