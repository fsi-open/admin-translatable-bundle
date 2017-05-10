<?php

namespace FSi\FixturesBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FilesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $removableFileType = TypeSolver::getFormType(
            'FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\RemovableFileType',
            'fsi_removable_file'
        );

        $builder->add('file', $removableFileType, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'FSi\FixturesBundle\Entity\File']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function getName()
    {
        return 'files';
    }
}
