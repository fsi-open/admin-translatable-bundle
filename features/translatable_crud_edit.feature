Feature: Edit translatable item
  In order to change existing translatable item in specific locale
  As an administrator
  I need to choose this locale from translatable locale list

  Background:
    Given the following admin translatable elements were registered
      | Service Id                    | Class                           |
      | fixtures_bundle.admin.events  | FSi\FixturesBundle\Admin\Events |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And default translatable locale is "en"
    And I add new event with name "Event en" in "en" locale

  Scenario: Edit event item in different translatable locale
    Given I am on the "Events list" page
    And I choose "pl" from translatable locale list
    And I edit first event on the list
    And I change "Name" field value to "Event pl"
    When I press "Save" button
    Then I should see event with name "Event pl"
    And I choose "en" from translatable locale list
    And I should see event with name "Event en"
