<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class TranslatableCollectionListener implements EventSubscriberInterface
{
    /**
     * @var TranslatableFormHelper
     */
    protected $translatableFormHelper;

    public function __construct(TranslatableFormHelper $translatableFormHelper)
    {
        $this->translatableFormHelper = $translatableFormHelper;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SET_DATA => ['onPreSetData', 10],
        ];
    }

    public function onPreSetData(FormEvent $event): void
    {
        $form = $event->getForm();
        $parent = $this->translatableFormHelper->getFirstTranslatableParent($form);

        if (
            true === $this->translatableFormHelper->isFormPropertyPathTranslatable($form)
            && false === $this->translatableFormHelper->isFormDataInCurrentLocale($parent)
        ) {
            $event->setData(null);
        }
    }
}
