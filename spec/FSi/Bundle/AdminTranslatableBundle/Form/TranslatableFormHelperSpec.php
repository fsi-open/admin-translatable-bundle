<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

class TranslatableFormHelperSpec extends ObjectBehavior
{
    public function let(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessor $propertyAccessor,
        EntityManagerInterface $manager,
        ClassMetadata $translatableMetadata,
        PropertyPath $propertyPath,
        FormInterface $form,
        FormConfigInterface $formConfig,
        FormInterface $parentForm,
        FormConfigInterface $parentFormConfig,
        FormInterface $grandParentForm,
        FormConfigInterface $grandParentFormConfig
    ): void {
        $this->beConstructedWith($managerRegistry, $translatableListener, $propertyAccessor);

        $propertyPath->__toString()->willReturn('translatable_property');
        $form->getPropertyPath()->willReturn($propertyPath);
        $form->getConfig()->willReturn($formConfig);

        $form->getParent()->willReturn($parentForm);
        $parentForm->getConfig()->willReturn($parentFormConfig);
        $parentFormConfig->getInheritData()->willReturn(true);
        $parentForm->getParent()->willReturn($grandParentForm);

        $data = new \stdClass();
        $grandParentForm->getNormData()->willReturn($data);
        $propertyAccessor->getValue($data, 'locale')->willReturn('en');

        $grandParentForm->getConfig()->willReturn($grandParentFormConfig);
        $grandParentFormConfig->getInheritData()->willReturn(false);
        $grandParentFormConfig->getDataClass()->willReturn('translatable_class');

        $managerRegistry->getManagerForClass('translatable_class')->willReturn($manager);
        $translatableListener->getExtendedMetadata($manager, 'translatable_class')->willReturn($translatableMetadata);
        $translatableListener->getLocale()->willReturn('de');
        $translatableMetadata->localeProperty = 'locale';
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
    }

    public function it_return_true_if_form_is_for_translatable_property(
        ClassMetadata $translatableMetadata,
        FormInterface $form
    ): void {
        $translatableMetadata->getTranslatableProperties()->willReturn(
            ['translations' => ['translatable_property' => 'translation_property']]
        );

        $this->isFormPropertyPathTranslatable($form)->shouldReturn(true);
    }

    public function it_return_false_if_form_is_not_for_translatable_property(
        ClassMetadata $translatableMetadata,
        FormInterface $form
    ): void {
        $translatableMetadata->getTranslatableProperties()->willReturn([]);
        $this->isFormPropertyPathTranslatable($form)->shouldReturn(false);
    }

    public function it_gets_locale_from_form_normalized_data(FormInterface $grandParentForm): void
    {
        $this->getFormNormDataLocale($grandParentForm)->shouldReturn('en');
    }

    public function it_check_form_data_locale_to_current(FormInterface $grandParentForm): void
    {
        $this->isFormDataInCurrentLocale($grandParentForm)->shouldReturn(false);
    }
}
