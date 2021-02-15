<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TranslatableFormHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class TranslatableCollectionListenerSpec extends ObjectBehavior
{
    public function let(
        TranslatableFormHelper $translatableFormHelper,
        FormEvent $event,
        FormInterface $form,
        FormInterface $parentForm
    ): void {
        $this->beConstructedWith($translatableFormHelper);

        $event->getForm()->willReturn($form);
        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
    }

    public function it_subscribe_to_pre_set_data(): void
    {
        $this->getSubscribedEvents()->shouldReturn([
            FormEvents::PRE_SET_DATA => ['onPreSetData', 10],
        ]);
    }

    public function it_do_nothing_if_not_translatable_property(
        TranslatableFormHelper $translatableFormHelper,
        FormEvent $event,
        FormInterface $form
    ): void {
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(false);
        $event->setData(Argument::any())->shouldNotBeCalled();
        $this->onPreSetData($event);
    }

    public function it_do_nothing_if_form_data_is_in_current_locale(
        TranslatableFormHelper $translatableFormHelper,
        FormEvent $event,
        FormInterface $form,
        FormInterface $parentForm
    ): void {
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(true);
        $event->setData(Argument::any())->shouldNotBeCalled();
        $this->onPreSetData($event);
    }

    public function it_clear_data_if_translatable_and_in_different_locale(
        TranslatableFormHelper $translatableFormHelper,
        FormEvent $event,
        FormInterface $form,
        FormInterface $parentForm
    ): void {
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(false);
        $event->setData(Argument::any())->shouldBeCalled();
        $this->onPreSetData($event);
    }
}
