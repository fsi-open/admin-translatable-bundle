<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Symfony\Component\HttpKernel\KernelInterface;

class AdminContext extends PageObjectContext implements KernelAwareInterface
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    function __construct()
    {
        $this->useContext('data', new DataContext());
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @Given /^the following services were registered$/
     */
    public function theFollowingServicesWereRegistered(TableNode $table)
    {
        foreach ($table->getHash() as $serviceRow) {
            expect($this->kernel->getContainer()->has($serviceRow['Id']))->toBe(true);
            expect($this->kernel->getContainer()->get($serviceRow['Id']))->toBeAnInstanceOf($serviceRow['Class']);
        }
    }

    /**
     * @Given /^I am on the "([^"]*)" page$/
     */
    public function iAmOnThePage($pageName)
    {
        $this->getPage($pageName)->open();
    }

    /**
     * @Given /^I am on the "([^"]*)" page with id (\d+)$/
     */
    public function iAmOnThePageWithId($pageName, $id)
    {
        $this->getPage($pageName)->open(array('id' => $id));
    }

    /**
     * @Given /^the following languages were defined$/
     */
    public function theFollowingLanguagesWereDefined(TableNode $languages)
    {
        $definedLanguages = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.languages');

        foreach ($languages as $language) {
            expect(in_array($language, $definedLanguages))->toBe(true);
        }
    }

    /**
     * @Then /^I should see translatable switcher$/
     */
    public function iShouldSeeTranslatableSwitcher()
    {
        expect($this->getPage('Admin Panel')->hasMenu())->toBe(true);
    }

    /**
     * @Given /^translatable switcher should have three options$/
     */
    public function translatableSwitcherShouldHaveThreeOptions()
    {
//        var_dump($this->getPage('Admin Panel')->getHtml()); die();
        expect($this->getPage('Admin Panel')->getNumberOfLanguageOptions())->toBe(3);
    }

    /**
     * @Given /^translatable switcher should be inactive$/
     */
    public function translatableSwitcherShouldBeInactive()
    {
        expect($this->getPage('Admin Panel')->isTranslatableSwitcherActive())->toBe(false);
    }
}
