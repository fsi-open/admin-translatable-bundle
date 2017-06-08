<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle;

use PhpSpec\ObjectBehavior;

class FSiAdminTranslatableBundleSpec extends ObjectBehavior
{
    function it_is_bundle()
    {
        $this->shouldHaveType('Symfony\Component\HttpKernel\Bundle\Bundle');
    }
}
