Feature: Deleting existing translatable item
  In order to delete existing translatable item
  As an administrator
  I can delete it in any defined locale

  Background:
    Given the following admin translatable elements were registered
      | Element Id  | Class                          |
      | admin_event | FSi\FixturesBundle\Admin\Event |
    And there are 3 events in each locale
    And default translatable locale is "en"

  @javascript
  Scenario: Delete translatable item
    Given I am on the "Events list" page with translatable locale "pl"
    When I check first item on the list
    And I choose "Delete" from batch action list and confirm it with "Ok"
    Then I should be redirected to "Events List" page with locale "pl"
    And I should see 2 events on the list
    And I choose "English" from translatable locale list
    And I should see 2 events on the list
