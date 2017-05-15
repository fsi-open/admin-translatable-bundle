<?php

namespace FSi\FixturesBundle\Form;

use FSi\Bundle\AdminTranslatableBundle\Form\TypeSolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $textareaType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text');
        $builder->add('text', $textareaType);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => 'FSi\FixturesBundle\Entity\Comment']);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    public function getName()
    {
        return 'comment';
    }
}
