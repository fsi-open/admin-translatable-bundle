<?php

namespace spec\FSi\Bundle\AdminTranslatableBundle\Form;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
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
    function let(
        ManagerRegistry $managerRegistry,
        TranslatableListener $translatableListener,
        PropertyAccessor $propertyAccessor,
        FSiFilePathResolver $filePathResolver
    ) {
        $this->beConstructedWith($managerRegistry, $translatableListener, $propertyAccessor, $filePathResolver);
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
        FormInterface $form,
        FormConfigInterface $formConfig,
        FormInterface $parentForm,
        FormConfigInterface $parentFormConfig,
        FormInterface $grandParentForm,
        FormConfigInterface $grandParentFormConfig
    ) {
        $view = new FormView();
        $fileView = new FormView($view);
        $view->children['translatable_property'] = $fileView;

        $propertyPath->__toString()->willReturn('translatable_property');
        $form->getPropertyPath()->willReturn($propertyPath);
        $form->getConfig()->willReturn($formConfig);
        $form->getName()->willReturn('translatable_property');

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
        FSiFilePathResolver $filePathResolver,
        ManagerRegistry $managerRegistry,
        ObjectManager $manager,
        TranslatableListener $translatableListener,
        ClassMetadata $translatableMetadata,
        File $uploadableFile,
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
        $fileView = new FormView($view);
        $removeView = new FormView($view);
        $view->children['translatable_property'] = $fileView;
        $view->children['remove'] = $removeView;

        $propertyPath->__toString()->willReturn('translatable_property');
        $form->getPropertyPath()->willReturn($propertyPath);
        $form->getConfig()->willReturn($formConfig);
        $form->getName()->willReturn('translatable_property');

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
        $view->vars['value'] = $data;
        $fileView->vars['value'] = $uploadableFile->getWrappedObject();
        $fileView->vars['data'] = $uploadableFile->getWrappedObject();
        $grandParentForm->getNormData()->willReturn($data);
        $propertyAccessor->getValue($data, 'locale')->willReturn('en');
        $translatableListener->getLocale()->willReturn('de');

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
