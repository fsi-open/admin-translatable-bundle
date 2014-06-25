Feature: List of elements
  In order to generate list of translatable elements in admin panel
  As a developer
  I need to install FSiAdminTranslatableBundle and configure events admin element

  Background:
    Given the following languages were defined
      | Language  |
      | en        |
      | pl        |
      | de        |
    And there are 5 events in each language

  Scenario: Accessing events list
    Given I am on the "Admin panel" page
    When I follow "Events" url from top bar
    Then I should see list with following columns
      | Column name   |
      | Name          |
    And I should see "Events List" page title "List of elements"

  Scenario: Changing the translatable language for events list
    Given I am on the "Events list" page
    And I click "pl" link from translatable language dropdown
    And I see events with name values
      | Name      |
      | Name pl 1 |
      | Name pl 2 |
      | Name pl 3 |
      | Name pl 4 |
      | Name pl 5 |
    When I click "en" link from translatable language dropdown
    Then I see events with name values
      | Name      |
      | Name en 1 |
      | Name en 2 |
      | Name en 3 |
      | Name en 4 |
      | Name en 5 |
