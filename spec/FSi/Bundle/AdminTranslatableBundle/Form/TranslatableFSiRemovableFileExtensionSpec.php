<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\Bundle\AdminTranslatableBundle\Form\TranslatableFormHelper;
use FSi\Bundle\DoctrineExtensionsBundle\Resolver\FSiFilePathResolver;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata;
use FSi\DoctrineExtensions\Translatable\TranslatableListener;
use FSi\DoctrineExtensions\Uploadable\File;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\PropertyAccess\PropertyPath;

class TranslatableFSiRemovableFileExtensionSpec extends ObjectBehavior
{
    function let(TranslatableFormHelper $translatableFormHelper, FSiFilePathResolver $filePathResolver)
    {
        $this->beConstructedWith($translatableFormHelper, $filePathResolver);
    }

    function it_is_form_type_extension()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\Form\AbstractTypeExtension');
    }

    function it_extends_text_form()
    {
        $this->getExtendedType()->shouldReturn('fsi_removable_file');
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
        FormView $view,
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ) {
        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormForTranslatableProperty($form)->willReturn(false);

        $this->finishView($view, $form, array());

        expect($view->vars['translatable'])->toBe(false);
        expect($view->vars['not_translated'])->toBe(false);
    }

    function it_sets_translatable_attribute_when_property_is_translatable(
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ) {
        $view = new FormView();
        $fileView = new FormView($view);
        $view->children['translatable_property'] = $fileView;

        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormForTranslatableProperty($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(false);

        $form->getName()->willReturn('translatable_property');

        $this->finishView($view, $form, array());

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(false);
    }

    function it_sets_not_translated_attribute_when_property_no_translation(
        FSiFilePathResolver $filePathResolver,
        File $uploadableFile,
        FormInterface $form,
        FormInterface $parentForm,
        TranslatableFormHelper $translatableFormHelper
    ) {
        $view = new FormView();
        $fileView = new FormView($view);
        $removeView = new FormView($view);
        $view->children['translatable_property'] = $fileView;
        $view->children['remove'] = $removeView;

        $translatableFormHelper->getFirstTranslatableParent($form)->willReturn($parentForm);
        $translatableFormHelper->isFormForTranslatableProperty($form)->willReturn(true);
        $translatableFormHelper->isFormDataInCurrentLocale($parentForm)->willReturn(false);
        $translatableFormHelper->getFormNormDataLocale($parentForm)->willReturn('en');

        $form->getName()->willReturn('translatable_property');

        $data = new \stdClass();
        $view->vars['value'] = $data;
        $fileView->vars['value'] = $uploadableFile->getWrappedObject();
        $fileView->vars['data'] = $uploadableFile->getWrappedObject();

        $filePathResolver->fileBasename($uploadableFile->getWrappedObject())->willReturn('default-locale-filename');
        $filePathResolver->fileUrl($uploadableFile->getWrappedObject())->willReturn('default-locale-url');

        $this->finishView($view, $form, array('remove_name' => 'remove'));

        expect($view->vars['translatable'])->toBe(true);
        expect($view->vars['not_translated'])->toBe(true);
        expect($view->vars['label_attr']['data-default-locale'])->toBe('en');
        expect($view->vars['label_attr']['data-default-locale-value'])->toBe('default-locale-filename');
        expect($view->vars['label_attr']['data-default-locale-url'])->toBe('default-locale-url');
        expect($fileView->vars['value'])->toBe(null);
        expect($fileView->vars['data'])->toBe(null);
        expect($removeView->vars['checked'])->toBe(true);
    }
}
