<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\EventListener;

use FSi\Bundle\AdminTranslatableBundle\Manager\LocaleManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface
{
    /**
     * @var LocaleManager
     */
    private $localeManager;

    public function __construct(LocaleManager $localeManager)
    {
        $this->localeManager = $localeManager;
    }

    public static function getSubscribedEvents(): array
    {
        return  [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $locale = $event->getRequest()->get('locale');
        if (null !== $locale && '' !== $locale) {
            $this->localeManager->setLocale($locale);
        }
    }
}
