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
        $this->useContext('TranslatableCRUD', new TranslatableCRUDContext());
    }

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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
        $definedLanguages = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        foreach ($languages as $language) {
            expect(in_array($language, $definedLanguages))->toBe(true);
        }
    }

    /**
     * @Then /^I should see translatable switcher on the "([^"]*)" page$/
     */
    public function iShouldSeeTranslatableSwitcherOnThePage($page)

    {
        expect($this->getPage($page)->hasTranslatableSwitcher())->toBe(true);
    }

    /**
     * @Given /^translatable switcher should have three options on the "([^"]*)" page$/
     */
    public function translatableSwitcherShouldHaveThreeOptionsOnThePage($page)

    {
        expect($this->getPage($page)->getNumberOfLanguageOptions())->toBe(3);
    }

    /**
     * @Given /^translatable switcher should be inactive on the "([^"]*)" page$/
     */
    public function translatableSwitcherShouldBeInactiveOnThePage($page)

    {
        expect($this->getPage($page)->isTranslatableSwitcherActive())->toBe(false);
    }

    /**
     * @Given /^I click "([^"]*)" link from translatable language dropdown$/
     */
    public function iClickLinkFromTranslatableLanguageDropdown($translatableLocale)
    {
        $this->getPage('Events List')->getTranslatableLanguageDropdown()->clickLink($translatableLocale);
    }

    /**
     * @Then /^I should see translatable dropdown with "([^"]*)"$/
     */
    public function iShouldSeeTranslatableDropdownWith($dropdownText)
    {
        expect($this->getPage('Events List')->getTranslatableLanguageDropdown()->hasLink($dropdownText))->toBe(true);
    }

    /**
     * @When /^I follow "([^"]*)" url from top bar$/
     */
    public function iFollowUrlFromTopBar($menuElement)
    {
        $this->getPage('Admin Panel')->getMenu()->clickLink($menuElement);
    }

    /**
     * @Given /^I should see "([^"]*)" page title "([^"]*)"$/
     */
    public function iShouldSeePageTitle($page, $title)
    {
        expect($this->getPage($page)->getTitle())->toBe($title);
    }

    /**
     * @Given /^I see events with column values$/
     */
    public function iSeeEventsWithColumnValues(TableNode $elements)
    {
        foreach ($elements->getHash() as $element) {
            expect($this->getElement('Grid')->hasCellWithValue($element['Name']))->toBe(true);
        }
    }

}
