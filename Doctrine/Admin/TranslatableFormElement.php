<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\FormElement as FormElement;

abstract class TranslatableFormElement extends FormElement implements TranslatableAwareElement
{
    use TranslatableAwareElementImpl;

    public function getRoute(): string
    {
        return 'fsi_admin_translatable_form';
    }

    public function getRouteParameters(): array
    {
        return $this->appendLocaleParameter(parent::getRouteParameters());
    }

    public function getSuccessRouteParameters(): array
    {
        return $this->appendLocaleParameter(parent::getSuccessRouteParameters());
    }
}
