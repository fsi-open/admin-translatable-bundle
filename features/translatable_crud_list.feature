Feature: List of elements
  In order to generate list of translatable elements in admin panel
  As a developer
  I need to install FSiAdminTranslatableBundle and configure events admin element

  Scenario: Accessing events list
    Given I am on the "Admin panel" page
    When I follow "Events" url from top bar
    Then I should see list with following columns
      | Column name   |
      | Name          |
    And I should see "Events List" page title "List of elements"
