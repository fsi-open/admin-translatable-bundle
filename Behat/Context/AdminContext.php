<?php

namespace FSi\Bundle\AdminTranslatableBundle\Behat\Context;

use Behat\Gherkin\Node\TableNode;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminTranslatableBundle\Behat\Context\Page\Element\Display;

class AdminContext extends DefaultContext
{
    /**
     * @Given /^I am on the "([^"]*)" page$/
     */
    public function iAmOnThePage($pageName)
    {
        $this->getPage($pageName)->open();
    }

    /**
     * @Given /^I am on the "([^"]*)" page with translatable locale "([^"]*)"$/
     * @Given /^I am on the "([^"]*)" page with default translatable locale "([^"]*)"$/
     */
    public function iAmOnThePageWithTranslatableLocale($pageName, $locale)
    {
        $this->getPage($pageName)->open(['locale' => $locale]);
    }

    /**
     * @Given /^I am on the "([^"]*)" page with id (\d+)$/
     */
    public function iAmOnThePageWithId($pageName, $id)
    {
        $this->getPage($pageName)->open(['id' => $id]);
    }

    /**
     * @Given /^the following translatable locales were defined$/
     */
    public function theFollowingTranslatableLocalesWereDefined(TableNode $languages)
    {
        $definedLanguages = $this->kernel->getContainer()->getParameter('fsi_admin_translatable.locales');

        foreach ($languages as $language) {
            expect(in_array($language['Locale'], $definedLanguages))->toBe(true);
        }
    }

    /**
     * @Then /^I should see translatable locale list$/
     */
    public function iShouldSeeTranslatableLocaleList()
    {
        expect($this->getElement('Top Menu')->hasTranslatableSwitcher())->toBe(true);
    }

    /**
     * @Given /^translatable locale list should have following locales$/
     */
    public function translatableSwitcherShouldHaveFollowingLocales(TableNode $locales)
    {
        foreach ($locales->getHash() as $locale) {
            expect($this->getElement('Top Menu')->hasFollowingLocales($locale['Locale']))->toBe(true);
        }
    }

    /**
     * @Given /^translatable locale list should be inactive$/
     */
    public function translatableLocaleListShouldBeInactive()
    {
        expect($this->getElement('Top Menu')->isTranslatableSwitcherActive())->toBe(false);
    }

    /**
     * @Given /^I choose "([^"]*)" from translatable locale list$/
     */
    public function iChooseLinkFromTranslatableLocaleList($translatableLocale)
    {
        $this->getElement('Top Menu')->clickTranslatableDropdown();
        $this->getElement('Top Menu')->findTranslatableLanguageElement($translatableLocale)->click();
    }

    /**
     * @Then /^I should see translatable list with "([^"]*)" option selected$/
     */
    public function iShouldSeeTranslatableListWithSelected($locale)
    {
        expect($this->getElement('Top Menu')->hasActiveTranslatableLanguage($locale))->toBe(true);
    }

    /**
     * @When /^I follow "([^"]*)" url from top bar$/
     * @Given /^I follow "([^"]*)" menu element$/
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
     * @Given /^the following non-translatable resources were registered$/
     * @Given /^the following translatable resources were registered$/
     */
    public function theFollowingAdminTranslatableElementsWereRegistered(TableNode $elements)
    {
        /** @var Manager $manager */
        $manager = $this->kernel->getContainer()->get('admin.manager');

        foreach ($elements->getHash() as $serviceRow) {
            expect($manager->hasElement($serviceRow['Element Id']))->toBe(true);
            expect($manager->getElement($serviceRow['Element Id']))->toBeAnInstanceOf($serviceRow['Class']);
        }
    }

    /**
     * @Given /^I should see "([^"]*)" page header "([^"]*)"$/
     */
    public function iShouldSeePageHeader($pageName, $headerContent)
    {
        expect($this->getPage($pageName)->getTitle())->toBe($headerContent);
    }

    /**
     * @Given /^there are following resources added to resource map$/
     */
    public function thereAreFollowingResourcesAddedToResourceMap(TableNode $resources)
    {
        foreach ($resources->getHash() as $resource) {
            expect($this->kernel->getContainer()
                ->get('fsi_resource_repository.map_builder')
                ->hasResource($resource['Key']))->toBe(true);

            if (isset($resource['Type'])) {
                expect($this->kernel->getContainer()
                    ->get('fsi_resource_repository.map_builder')
                    ->getResource($resource['Key']))->toBeAnInstanceOf(
                        sprintf(
                            'FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\%sType',
                            ucfirst($resource['Type'])
                        )
                    );
            }
        }
    }

    /**
     * @Given /^I fill form "([^"]*)" field with "([^"]*)"$/
     */
    public function iFillFormFieldWith($field, $value)
    {
        $this->getElement('Form')->fillField($field, $value);
    }

    /**
     * @Given /^I should see form "([^"]*)" field with value "([^"]*)"$/
     */
    public function iShouldSeeFormFieldWithValue($field, $value)
    {
        expect($this->getElement('Form')->findField($field)->getValue())->toBe($value);
    }

    /**
     * @Given /^I should see form "([^"]*)" field with empty value$/
     */
    public function iShouldSeeFormFieldWithEmptyValue($field)
    {
        expect($this->getElement('Form')->findField($field)->getValue())->toBe('');
    }

    /**
     * @Given /^I should see form "([^"]*)" file field with empty value$/
     */
    public function iShouldSeeFormFileFieldWithEmptyValue($field)
    {
        expect($this->getElement('Form')->findField($field)->getParent()->find('css', 'a'))->toBe(null);
    }

    /**
     * @Given /^I should see form "([^"]*)" collection with (\d+) value$/
     */
    public function iShouldSeeFormCollectionWithValue($field, $count)
    {
        $label = $this->getElement('Form')->findLabel($field);
        expect($label)->toNotBe(null);

        expect(count($label->getParent()->findAll('css', 'div[data-prototype] > .collection-items > div.form-group')))->toBe((int)$count);
    }

    /**
     * @Given /^I should see form "([^"]*)" collection with empty value$/
     */
    public function iShouldSeeFormCollectionWithEmptyValue($field)
    {
        $label = $this->getElement('Form')->findLabel($field);
        expect($label)->toNotBe(null);

        expect(count($label->getParent()->findAll('css', 'div[data-prototype] > div.form-group')))->toBe(0);
    }

    /**
     * @Given /^form "([^"]*)" field should have translatable flag$/
     */
    public function FormFieldShouldHaveTranslatableFlag($field)
    {
        $field = $this->getElement('Form')->findField($field);
        $fieldLabel = $this->getElement('Form')->find('css', sprintf('label[for="%s"]', $field->getAttribute('id')));
        expect($fieldLabel->has('css', 'i.glyphicon-flag'))->toBe(true);
    }

    /**
     * @Given /^form "([^"]*)" field should have badge with "([^"]*)" default locale$/
     */
    public function formFieldShouldHaveBadgeWithDefaultLocale($field, $defaultLocale)
    {
        $field = $this->getElement('Form')->findField($field);
        $fieldLabel = $this->getElement('Form')->find('css', sprintf('label[for="%s"]', $field->getAttribute('id')));
        expect($fieldLabel->has('css', sprintf('.badge:contains("%s")', $defaultLocale)))->toBe(true);
    }

    /**
     * @Given /^form "([^"]*)" field should not have badge with default locale$/
     */
    public function formFieldShouldNotHaveBadgeWithDefaultLocale($field)
    {
        $field = $this->getElement('Form')->findField($field);
        $fieldLabel = $this->getElement('Form')->find('css', sprintf('label[for="%s"]', $field->getAttribute('id')));
        expect($fieldLabel->has('css', '.badge'))->toBe(false);
    }

    /**
     * @When /^I click default locale badge for "([^"]*)" field$/
     */
    public function iClickDefaultLocaleBadgeForField($field)
    {
        $element = $this->getElement('Form');
        usleep(5000);
        $fieldLabel = $element->find(
            'css',
            sprintf('label[for="%s"]', $element->findField($field)->getAttribute('id'))
        );
        $fieldLabel->find('css', '.badge')->click();
        usleep(5000);
    }

    /**
     * @Given /^I change first comment\'s text to "([^"]*)"$/
     */
    public function iChangeFirstCommentsTextTo($commentText)
    {
        $this->getElement('Form')->fillField('form_comments_0_text', $commentText);
    }

    /**
     * @Then /^I should see one comment with text "([^"]*)"$/
     */
    public function iShouldSeeOneCommentWithText($commentText)
    {
        expect($this->getElement('Form')->findField('form_comments_0_text')->getValue())->toBe($commentText);
    }

    /**
     * @Then /^I should see row "([^"]*)" with value "([^"]*)"$/
     */
    public function iShouldSeeRowWithValue($name, $value)
    {
        /** @var Display $display */
        $display = $this->getElement('Display');

        expect($display->getRowValue($name)->getText())->toBe($value);
    }
}
