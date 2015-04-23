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

class TranslatableTextExtensionSpec extends ObjectBehavior
{
    function let(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessor $propertyAccessor
    ) {
        $this->beConstructedWith($managerRegistry, $translatableListener, $propertyAccessor);
    }

    function it_is_form_type_extension()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\Form\AbstractTypeExtension');
    }

    function it_extends_text_form()
    {
        $this->getExtendedType()->shouldReturn('text');
    }

    function it_does_nothing_if_form_has_no_property_path(
        FormView $view,
        FormInterface $form
    ) {
        $form->getPropertyPath()->willReturn(null);

        $this->finishView($view, $form, array());
    }

    function it_does_nothing_if_form_has_no_translatable_parent(
        FormView $view,
        FormInterface $form,
        FormConfigInterface $formConfig,
        FormInterface $parentForm,
        FormConfigInterface $parentFormConfig
    ) {
        $form->getPropertyPath()->willReturn('translatable_property');
        $form->getConfig()->willReturn($formConfig);

        $form->getParent()->willReturn($parentForm);
        $parentForm->getConfig()->willReturn($parentFormConfig);
        $parentFormConfig->getInheritData()->willReturn(true);

        $parentForm->getParent()->willReturn(null);

        $this->finishView($view, $form, array());
    }

    function it_does_nothing_if_forms_property_is_not_translatable_in_first_translatable_parent(
        ManagerRegistry $managerRegistry,
        ObjectManager $manager,
        TranslatableListener $translatableListener,
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
        $propertyPath->__toString()->willReturn('translatable_property');
        $form->getPropertyPath()->willReturn($propertyPath);
        $form->getConfig()->willReturn($formConfig);

        $form->getParent()->willReturn($parentForm);
        $parentForm->getConfig()->willReturn($parentFormConfig);
        $parentFormConfig->getInheritData()->willReturn(true);

        $parentForm->getParent()->willReturn($grandParentForm);
        $grandParentForm->getConfig()->willReturn($grandParentFormConfig);
        $grandParentFormConfig->getInheritData()->willReturn(false);
        $grandParentFormConfig->getDataClass()->willReturn('translatable_class');

        $managerRegistry->getManagerForClass('translatable_class')->willReturn($manager);
        $translatableListener->getExtendedMetadata($manager, 'translatable_class')->willReturn($translatableMetadata);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()->willReturn(array());

        $this->finishView($view, $form, array());

        expect($view->vars['translatable'])->toBe(false);
        expect($view->vars['not_translated'])->toBe(false);
    }

    function it_sets_translatable_attribute_when_property_is_translatable(
        ManagerRegistry $managerRegistry,
        ObjectManager $manager,
        TranslatableListener $translatableListener,
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
        $propertyPath->__toString()->willReturn('translatable_property');
        $form->getPropertyPath()->willReturn($propertyPath);
        $form->getConfig()->willReturn($formConfig);

        $form->getParent()->willReturn($parentForm);
        $parentForm->getConfig()->willReturn($parentFormConfig);
        $parentFormConfig->getInheritData()->willReturn(true);

        $parentForm->getParent()->willReturn($grandParentForm);
        $grandParentForm->getConfig()->willReturn($grandParentFormConfig);
        $grandParentFormConfig->getInheritData()->willReturn(false);
        $grandParentFormConfig->getDataClass()->willReturn('translatable_class');

        $managerRegistry->getManagerForClass('translatable_class')->willReturn($manager);
        $translatableListener->getExtendedMetadata($manager, 'translatable_class')->willReturn($translatableMetadata);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->getTranslatableProperties()->willReturn(
            array('translations' => array('translatable_property' => 'translation_property'))
        );

        $this->finishView($view, $form, array());

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(false);
    }

    function it_sets_not_translated_attribute_when_property_no_translation(
        ManagerRegistry $managerRegistry,
        ObjectManager $manager,
        TranslatableListener $translatableListener,
        ClassMetadata $translatableMetadata,
        PropertyPath $propertyPath,
        PropertyAccessor $propertyAccessor,
        FormInterface $form,
        FormConfigInterface $formConfig,
        FormInterface $parentForm,
        FormConfigInterface $parentFormConfig,
        FormInterface $grandParentForm,
        FormConfigInterface $grandParentFormConfig
    ) {
        $view = new FormView();
        $propertyPath->__toString()->willReturn('translatable_property');
        $form->getPropertyPath()->willReturn($propertyPath);
        $form->getConfig()->willReturn($formConfig);

        $form->getParent()->willReturn($parentForm);
        $parentForm->getConfig()->willReturn($parentFormConfig);
        $parentFormConfig->getInheritData()->willReturn(true);

        $parentForm->getParent()->willReturn($grandParentForm);
        $grandParentForm->getConfig()->willReturn($grandParentFormConfig);
        $grandParentFormConfig->getInheritData()->willReturn(false);
        $grandParentFormConfig->getDataClass()->willReturn('translatable_class');

        $managerRegistry->getManagerForClass('translatable_class')->willReturn($manager);
        $translatableListener->getExtendedMetadata($manager, 'translatable_class')->willReturn($translatableMetadata);
        $translatableMetadata->hasTranslatableProperties()->willReturn(true);
        $translatableMetadata->localeProperty = 'locale';
        $translatableMetadata->getTranslatableProperties()->willReturn(
            array('translations' => array('translatable_property' => 'translation_property'))
        );

        $data = new \stdClass();
        $view->vars['value'] = 'default-locale-value';
        $grandParentForm->getData()->willReturn($data);
        $propertyAccessor->getValue($data, 'locale')->willReturn('en');
        $translatableListener->getLocale()->willReturn('de');

        $this->finishView($view, $form, array());

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(true);
        expect($view->vars['label_attr']['data-default-locale'])->toBe('en');
        expect($view->vars['label_attr']['data-default-locale-value'])->toBe('default-locale-value');
        expect($view->vars['value'])->toBe(null);
    }
}
