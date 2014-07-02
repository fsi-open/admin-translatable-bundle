Feature: Create element
  In order to create new element
  As a developer
  I need to install FSiAdminTranslatableBundle and configure form

  Background:
    Given the following admin translatable elements were registered
      | Service Id                    | Class                           |
      | fixtures_bundle.admin.events  | FSi\FixturesBundle\Admin\Events |
    And the following languages were defined
      | Language  |
      | en        |
      | pl        |
      | de        |
    And default translatable language is "en"

  Scenario: Create event element with default translatable language
    Given I add new event with name "Event en" and language "en"
    And I am on the "Events list" page
    When I click "pl" link from translatable language dropdown
    Then I should see event with default name "Event en"

  Scenario: Create event element with with another translatable language than default
    Given I add new event with name "Event en" and language "de"
    And I am on the "Events list" page
    When I click "en" link from translatable language dropdown
    Then I should see event with empty name value
