<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

class TranslatableFormHelperSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessor $propertyAccessor,
        ObjectManager $manager,
        ClassMetadata $translatableMetadata,
        PropertyPath $propertyPath,
        FormView $view,
        FormInterface $form,
        FormConfigInterface $formConfig,
        FormInterface $parentForm,
        FormConfigInterface $parentFormConfig,
        FormInterface $grandParentForm,
        FormConfigInterface $grandParentFormConfig
    ) {
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

    function it_return_true_if_form_is_for_translatable_property(
        ClassMetadata $translatableMetadata,
        FormInterface $form
    ) {
        $translatableMetadata->getTranslatableProperties()->willReturn(
            ['translations' => ['translatable_property' => 'translation_property']]
        );

        $this->isFormPropertyPathTranslatable($form)->shouldReturn(true);
    }

    function it_return_false_if_form_is_not_for_translatable_property(
        ClassMetadata $translatableMetadata,
        FormInterface $form
    ) {
        $translatableMetadata->getTranslatableProperties()->willReturn([]);
        $this->isFormPropertyPathTranslatable($form)->shouldReturn(false);
    }

    function it_gets_locale_from_form_normalized_data(FormInterface $grandParentForm)
    {
        $this->getFormNormDataLocale($grandParentForm)->shouldReturn('en');
    }

    function it_check_form_data_locale_to_current(FormInterface $grandParentForm)
    {
        $this->isFormDataInCurrentLocale($grandParentForm)->shouldReturn(false);
    }
}
