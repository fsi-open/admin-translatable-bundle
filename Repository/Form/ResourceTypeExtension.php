<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Repository\Form;

use FSi\Bundle\AdminTranslatableBundle\Repository\TranslatableMapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Form\Type\ResourceType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ResourceTypeExtension extends AbstractTypeExtension
{
    /**
     * @var TranslatableMapBuilder
     */
    private $mapBuilder;

    public static function getExtendedTypes(): iterable
    {
        return [ResourceType::class];
    }

    public function __construct(TranslatableMapBuilder $mapBuilder)
    {
        $this->mapBuilder = $mapBuilder;
    }

    public function finishView(FormView $view, FormInterface $form, array $options): void
    {
        $resource = $this->mapBuilder->getResource($options['resource_key']);
        $translatable = ($options['resource_key'] !== $resource->getName());
        foreach ($view->children as $child) {
            $child->vars['translatable'] = $translatable;
        }
    }
}
