<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Repository\Form;

use FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ResourceTypeExtension extends AbstractTypeExtension
{
    /**
     * @var TranslatableMapBuilder
     */
    private $mapBuilder;

    public function __construct(TranslatableMapBuilder $mapBuilder)
    {
        $this->mapBuilder = $mapBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedType()
    {
        return 'resource';
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $resource = $this->mapBuilder->getResource($options['resource_key']);
        $translatable = ($options['resource_key'] !== $resource->getName());
        foreach ($view->children as $child) {
            $child->vars['translatable'] = $translatable;
        }
    }
}
