Feature: Deleting existing translatable item
  In order to delete existing translatable item
  As an administrator
  I can delete it in any defined locale

  Background:
    Given the following admin translatable elements were registered
      | Service Id                    | Class                           |
      | fixtures_bundle.admin.events  | FSi\FixturesBundle\Admin\Events |
    And there are 3 events in each locale
    And default translatable locale is "en"

  @javascript
  Scenario: Delete translatable item
    Given I am on the "Events list" page with translatable locale "pl"
    When I check first item on the list
    And I choose "Delete" from batch action list and confirm it with "Ok"
    Then I should be redirected to confirmation page with message
    """
    Are you sure you want to delete 1 from selected elements?
    """
    When I press "Yes"
    Then I should be redirected to "Events List" page
    And I should see 2 events on the list
    And I choose "en" from translatable locale list
    And I should see 2 events on the list
