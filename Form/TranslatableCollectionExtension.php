<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class TranslatableCollectionExtension extends AbstractSimpleTranslatableExtension
{
    /**
     * @var TranslatableCollectionListener
     */
    private $listener;

    public function __construct(
        TranslatableFormHelper $translatableFormHelper,
        TranslatableCollectionListener $listener
    ) {
        parent::__construct($translatableFormHelper);
        $this->listener = $listener;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return 'collection';
    }

    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->listener);
    }
}
