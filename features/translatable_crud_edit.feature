Feature: Edit element
  In order to edit existing element
  As a developer
  I need to install FSiAdminTranslatableBundle and configure form

  Background:
    Given the following languages were defined
      | Language  |
      | en        |
      | pl        |
      | de        |
    And default translatable language is "en"
    And I add new event with name "Event en" and language "en"

  Scenario: Edit event element in different translatable language
    Given I am on the "Events list" page
    And I click "pl" link from translatable language dropdown
    And I edit only event element
    And I change form "Name" field value to "Event pl"
    When I press form "Save" button
    Then I should see event with name "Event pl"
    And I click "en" link from translatable language dropdown
    And I should see event with name "Event en"
