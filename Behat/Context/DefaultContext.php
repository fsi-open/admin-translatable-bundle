<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class DefaultContext extends PageObjectContext implements KernelAwareContext, MinkAwareContext
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var Mink
     */
    protected $mink;

    /**
     * @var array
     */
    private $minkParameters;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function setMink(Mink $mink)
    {
        $this->mink = $mink;
    }

    public function setMinkParameters(array $parameters)
    {
        $this->minkParameters = $parameters;
    }

    protected function getSession(): Session
    {
        return $this->mink->getSession();
    }
}
