Feature: List of translatable items
  In order to see list of translatable items in different locales
  As an administrator
  I can change translatable locale on the list page

  Background:
    Given the following admin translatable elements were registered
      | Element Id  | Class                          |
      | admin_event | FSi\FixturesBundle\Admin\Event |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And there are 5 events in each locale

  Scenario: Changing the translatable locale for events list
    Given I am on the "Events list" page
    And I choose "Polish" from translatable locale list
    And I should see following list
      | Name      |
      | Name pl 1 |
      | Name pl 2 |
      | Name pl 3 |
      | Name pl 4 |
      | Name pl 5 |
    When I choose "English" from translatable locale list
    Then I should see following list
      | Name      |
      | Name en 1 |
      | Name en 2 |
      | Name en 3 |
      | Name en 4 |
      | Name en 5 |

  Scenario: Filtering events list
    Given I am on the "Events list" page
    And I choose "Polish" from translatable locale list
    And I should see simple text filter "Name"
    When I fill simple text filter "Name" with value "pl 3"
    And I press "Search" button
    Then I should see following list
      | Name      |
      | Name pl 3 |
    And simple text filter "Name" should be filled with value "pl 3"

  @javascript
  Scenario: Inline edit name on events list
    Given I am on the "Events list" page
    And I choose "Polish" from translatable locale list
    And I click edit in "Name" column in third row
    And I should see popover with value "Name pl 3" in field "Name"
    And I fill in field "Name" with value "Name pl 3 changed" at popover
    And I submit popover form
    Then I should see following list
      | Name              |
      | Name pl 1         |
      | Name pl 2         |
      | Name pl 3 changed |
      | Name pl 4         |
      | Name pl 5         |
    When I choose "English" from translatable locale list
    Then I should see following list
      | Name      |
      | Name en 1 |
      | Name en 2 |
      | Name en 3 |
      | Name en 4 |
      | Name en 5 |
