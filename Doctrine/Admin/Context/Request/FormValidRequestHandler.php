<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminTranslatableBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\FormValidRequestHandler as BaseFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FormValidRequestHandler extends BaseFormValidRequestHandler
{
    protected function getRedirectResponse(FormEvent $event, Request $request)
    {
        if ($request->query->has('redirect_uri')) {
            return new RedirectResponse($request->query->get('redirect_uri'));
        }

        /** @var RedirectableElement $element */
        $element = $event->getElement();

        return new RedirectResponse(
            $this->router->generate(
                $element->getSuccessRoute(),
                array_merge(
                    $element->getSuccessRouteParameters(),
                    array(
                        'locale' => $event->getRequest()->get('locale'), // FIXME: localManager::getLocale?
                    )
                )
            )
        );
    }

}
