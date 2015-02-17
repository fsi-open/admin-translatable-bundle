Feature: List of translatable items
  In order to see list of translatable items in different locales
  As an administrator
  I can change translatable locale on the list page

  Background:
    Given the following admin translatable elements were registered
      | Service Id                  | Class                          |
      | fixtures_bundle.admin.event | FSi\FixturesBundle\Admin\Event |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And there are 5 events in each locale

  Scenario: Changing the translatable locale for events list
    Given I am on the "Events list" page
    And I choose "pl" from translatable locale list
    And I should see events with following names
      | Name      |
      | Name pl 1 |
      | Name pl 2 |
      | Name pl 3 |
      | Name pl 4 |
      | Name pl 5 |
    When I choose "en" from translatable locale list
    Then I should see events with following names
      | Name      |
      | Name en 1 |
      | Name en 2 |
      | Name en 3 |
      | Name en 4 |
      | Name en 5 |

  Scenario: Filtering events list
    Given I am on the "Events list" page
    And I choose "pl" from translatable locale list
    And I should see simple text filter "Name"
    When I fill simple text filter "Name" with value "pl 3"
    And I press "Search" button
    Then I should see events with following names
      | Name      |
      | Name pl 3 |
    And simple text filter "Name" should be filled with value "pl 3"
