<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form\Type;

use FSi\Bundle\AdminTranslatableBundle\Form\EventListener\AddTranslatableResourceKeySubscriber;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use FSi\Bundle\ResourceRepositoryBundle\Form\Type\ResourceType as BaseResourceType;

class TranslatableResourceType extends BaseResourceType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->resourceClass
            )
        );

        $resolver->setOptional(
            array(
                'translatable_locale'
            )
        );

        $resolver->setRequired(
            array(
                'resource_key'
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventSubscriber(new AddTranslatableResourceKeySubscriber());
    }
}
