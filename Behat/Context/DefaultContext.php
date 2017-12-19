<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Mink;
use Behat\Mink\Session;
use Behat\MinkExtension\Context\MinkAwareContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use RuntimeException;
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

    protected function getDriver(): DriverInterface
    {
        return $this->getSession()->getDriver();
    }

    /**
     * @param string $selector
     * @param bool $xpath
     * @throws RuntimeException
     */
    protected function waitUntilObjectVisible(string $selector, bool $xpath = false): void
    {
        if (!$this->isSeleniumDriver()) {
            throw new RuntimeException('Selenium driver is required for this function!');
        }

        if ($xpath) {
            $condition = sprintf(
                "document.evaluate('%s', document, null, XPathResult.ANY_TYPE, null).offsetWidth > 0"
                . " && document.evaluate('%s', document, null, XPathResult.ANY_TYPE, null).offsetHeight > 0",
                $selector,
                $selector
            );
        } else {
            $condition = sprintf(
                "document.querySelectorAll('%s').offsetWidth > 0 && document.querySelectorAll('%s').offsetHeight > 0",
                $selector,
                $selector
            );
        }

        $this->getDriver()->wait(10000, $condition);
    }

    protected function isSeleniumDriver(): bool
    {
        return $this->getDriver() instanceof Selenium2Driver;
    }
}
