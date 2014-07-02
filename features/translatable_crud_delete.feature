Feature: Deleting existing elements
  In order to delete existing elements
  As a developer
  I need to install FSiAdminBundle and configure admin element

  Background:
    Given the following admin translatable elements were registered
      | Service Id                    | Class                           |
      | fixtures_bundle.admin.events  | FSi\FixturesBundle\Admin\Events |
    And there are 3 events in each language
    And default translatable language is "en"

  @javascript
  Scenario: Delete single news
    Given I am on the "Events list" page with translatable language "en"
    When I press checkbox in first column in first row
    And I choose action "Delete" from actions
    And I press confirmation button "Ok"
    Then I should be redirected to confirmation page with message
    """
    Are you sure you want to delete 1 from selected elements?
    """
    When I press "Yes"
    Then I should be redirected to "Events List" page
    And I should see 2 events on the list
    And I click "pl" link from translatable language dropdown
    And I should see 2 events on the list
