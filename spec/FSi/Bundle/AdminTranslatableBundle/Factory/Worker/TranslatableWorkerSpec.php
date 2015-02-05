<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Factory\Worker;


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
        $this->shouldImplement('FSi\Bundle\AdminBundle\Factory\Worker');
    }

    function it_mounts_to_translatable_element(TranslatableCRUDElement $element, LocaleManager $localeManager)
    {
        $element->setLocaleManager($localeManager)->shouldBeCalled();

        $this->mount($element);
    }
}
