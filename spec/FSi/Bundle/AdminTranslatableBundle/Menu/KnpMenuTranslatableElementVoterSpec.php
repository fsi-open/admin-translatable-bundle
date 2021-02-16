<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use PhpSpec\ObjectBehavior;

class KnpMenuTranslatableElementVoterSpec extends ObjectBehavior
{
    public function let(VoterInterface $menuElementVoter, LocaleManager $localeManager): void
    {
        $this->beConstructedWith($menuElementVoter, $localeManager);
    }

    public function it_should_pass_through_if_inner_voter_cant_decide(
        VoterInterface $menuElementVoter,
        ItemInterface $item
    ): void {
        $menuElementVoter->matchItem($item)->willReturn(null);

        $this->matchItem($item)->shouldReturn(null);
    }

    public function it_should_correctly_match_the_same_elements_with_different_locale(
        VoterInterface $menuElementVoter,
        ItemInterface $item,
        LocaleManager $localeManager
    ): void {
        $localeManager->getLocale()->willReturn('pl');

        $menuElementVoter->matchItem($item)->willReturn(true);

        $item->getExtra('routes', [])->willReturn([
            0 => ['parameters' => ['locale' => 'en']]
        ]);

        $this->matchItem($item)->shouldReturn(false);

        $item->getExtra('routes', [])->willReturn([
            0 => ['parameters' => ['locale' => 'pl']]
        ]);

        $this->matchItem($item)->shouldReturn(true);

        $item->getExtra('routes', [])->willReturn([
            0 => ['parameters' => ['locale' => 'de']]
        ]);

        $this->matchItem($item)->shouldReturn(false);
    }
}
