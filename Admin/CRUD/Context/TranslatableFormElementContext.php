<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\FormElementContext;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\TranslatableFormElement;

class TranslatableFormElementContext extends FormElementContext
{
    /**
     * @var string
     */
    private $formTemplate;

    /**
     * @param array $requestHandlers
     * @param string $formTemplate
     */
    public function __construct($requestHandlers, $formTemplate)
    {
        parent::__construct($requestHandlers);

        $this->formTemplate = $formTemplate;
    }

    /**
     * {@inheritdoc}
     */
    public function hasTemplateName()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplateName()
    {
        return $this->element->hasOption('template_form') ?
            $this->element->getOption('template_form') : $this->formTemplate;
    }

    /**
     * {@inheritdoc}
     */
    protected function supportsElement(Element $element)
    {
        if (!parent::supportsElement($element)) {
            return false;
        }

        return $element instanceof TranslatableFormElement;
    }
}
