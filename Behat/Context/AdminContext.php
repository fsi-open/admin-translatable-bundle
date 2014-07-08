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
     * @Given /^I am on the "([^"]*)" page with translatable locale "([^"]*)"$/
     */
    public function iAmOnThePageWithTranslatableLocale($pageName, $locale)
    {
        $this->getPage($pageName)->open(array('locale' => $locale));
    }

    /**
     * @Given /^I am on the "([^"]*)" page with id (\d+)$/
     */
    public function iAmOnThePageWithId($pageName, $id)
    {
        $this->getPage($pageName)->open(array('id' => $id));
    }

    /**
     * @Given /^the following translatable locales were defined$/
     */
    public function theFollowingTranslatableLocalesWereDefined(TableNode $languages)
    {
        $definedLanguages = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        foreach ($languages as $language) {
            expect(in_array($language, $definedLanguages))->toBe(true);
        }
    }

    /**
     * @Then /^I should see translatable locale list$/
     */
    public function iShouldSeeTranslatableLocaleList()
    {
        expect($this->getPage('Events List')->hasTranslatableSwitcher())->toBe(true);
    }

    /**
     * @Given /^translatable locale list should have following locales$/
     */
    public function translatableSwitcherShouldHaveFollowingLocales(TableNode $locales)
    {
        foreach ($locales->getHash() as $locale) {
            expect($this->getPage('Events List')->hasFollowingLocales($locale['Locale']))->toBe(true);
        }
    }

    /**
     * @Given /^translatable locale list should be inactive$/
     */
    public function translatableLocaleListShouldBeInactive()
    {
        expect($this->getPage('Events List')->isTranslatableSwitcherActive())->toBe(false);
    }

    /**
     * @Given /^I choose "([^"]*)" from translatable locale list$/
     */
    public function iChooseLinkFromTranslatableLocaleList($translatableLocale)
    {
        $this->getPage('Events List')->clickTranslatableDropdown();
        $this->getPage('Events List')->findTranslatableLanguageElement($translatableLocale)->click();
    }

    /**
     * @Then /^I should see translatable list with "([^"]*)" option selected$/
     */
    public function iShouldSeeTranslatableListWithSelected($locale)
    {
        expect($this->getPage('Events List')->hasActiveTranslatableLanguage($locale))->toBe(true);
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
     * @Given /^the following admin translatable elements were registered$/
     * @Given /^the following admin non-translatable elements were registered$/
     */
    public function theFollowingAdminTranslatableElementsWereRegistered(TableNode $elements)
    {
        foreach ($elements->getHash() as $serviceRow) {
            expect($this->kernel->getContainer()->has($serviceRow['Service Id']))->toBe(true);
            expect($this->kernel->getContainer()->get($serviceRow['Service Id']))->toBeAnInstanceOf($serviceRow['Class']);
        }
    }
}
