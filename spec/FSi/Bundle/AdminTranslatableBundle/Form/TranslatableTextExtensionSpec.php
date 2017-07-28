<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use FSi\Bundle\AdminTranslatableBundle\Form\TranslatableFormHelper;
use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyPath;

class TranslatableTextExtensionSpec extends ObjectBehavior
{
    function let(TranslatableFormHelper $translatableFormHelper)
    {
        $this->beConstructedWith($translatableFormHelper);
    }

    function it_is_form_type_extension()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\Form\AbstractTypeExtension');
    }

    function it_extends_text_form()
    {
        $this->getExtendedType()->shouldReturn(
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text')
        );
    }

    function it_does_nothing_if_form_has_no_property_path(
        FormView $view,
        FormInterface $form
    ) {
        $form->getPropertyPath()->willReturn(null);

        $this->finishView($view, $form, []);
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

        $this->finishView($view, $form, []);
    }

    function it_does_nothing_if_forms_property_is_not_translatable_in_first_translatable_parent(
        ManagerRegistry $managerRegistry,
        EntityManagerInterface $manager,
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
        $translatableMetadata->getTranslatableProperties()->willReturn([]);

        $this->finishView($view, $form, []);

        expect($view->vars['translatable'])->toBe(false);
        expect($view->vars['not_translated'])->toBe(false);
    }

    function it_sets_translatable_attribute_when_property_is_translatable(
        FormView $view,
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ) {
        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(true);

        $this->finishView($view, $form, []);

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(false);
    }

    function it_sets_not_translated_attribute_when_property_no_translation(
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ) {
        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormPropertyPathTranslatable($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(false);
        $translatableFormHelper->getFormNormDataLocale($parentForm)->willReturn('en');

        $view = new FormView();
        $view->vars['value'] = 'default-locale-value';

        $this->finishView($view, $form, []);

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(true);
        expect($view->vars['label_attr']['data-default-locale'])->toBe('en');
        expect($view->vars['label_attr']['data-default-locale-value'])->toBe('default-locale-value');
        expect($view->vars['value'])->toBe(null);
    }
}
