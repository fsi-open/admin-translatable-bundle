<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Menu;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\Request;

use function array_key_exists;

class KnpMenuTranslatableElementVoter implements VoterInterface
{
    /**
     * @var VoterInterface
     */
    private $menuElementVoter;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(VoterInterface $menuElementVoter, LocaleManager $localeManager)
    {
        $this->menuElementVoter = $menuElementVoter;
        $this->localeManager = $localeManager;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;

        if (true === method_exists($this->menuElementVoter, 'setRequest')) {
            $this->menuElementVoter->setRequest($request);
        }
    }

    public function matchItem(ItemInterface $item): ?bool
    {
        $elementMatch = $this->menuElementVoter->matchItem($item);

        if (false === $elementMatch || null === $elementMatch) {
            return $elementMatch;
        }

        $currentLocale = $this->localeManager->getLocale();
        $routes = (array) $item->getExtra('routes', []);

        foreach ($routes as $testedRoute) {
            $routeParameters = $testedRoute['parameters'];

            if (false === array_key_exists('locale', $routeParameters)) {
                continue;
            }

            return $routeParameters['locale'] === $currentLocale;
        }

        return $elementMatch;
    }
}
