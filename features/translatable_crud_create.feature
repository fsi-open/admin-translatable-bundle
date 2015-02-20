Feature: Creating new translatable item
  In order to create new translatable item
  As an administrator
  I can fill a translatable item's form in any defined locale

  Background:
    Given the following admin translatable elements were registered
      | Element Id  | Class                          |
      | admin_event | FSi\FixturesBundle\Admin\Event |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And default translatable locale is "en"

  Scenario: Create event element with default translatable locale
    Given I add new event with name "Event en" in "en" locale
    And I am on the "Events list" page
    When I choose "Polish" from translatable locale list
    Then I should see event with default name "Event en"

  Scenario: Create event element with translatable locale different than default
    Given I add new event with name "Event en" in "de" locale
    And I am on the "Events list" page
    When I choose "English" from translatable locale list
    Then I should see event with empty name
