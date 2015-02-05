Feature: Edit translatable item
  In order to change existing translatable item in specific locale
  As an administrator
  I need to choose this locale from translatable locale list

  Background:
    Given the following admin translatable elements were registered
      | Element Id  | Class                          |
      | admin_event | FSi\FixturesBundle\Admin\Event |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And default translatable locale is "en"
    And I add new event with name "Event en" in "en" locale
    And I add new comment with text "great news" to the news with name "Event en" in "en" locale

  Scenario: Edit event item in different translatable locale
    Given I am on the "Events list" page
    And I choose "pl" from translatable locale list
    And I edit first event on the list
    And I change "Name" field value to "Event pl"
    When I press "Save" button
    Then I should see event with name "Event pl"
    And I choose "en" from translatable locale list
    And I should see event with name "Event en"

  Scenario: Edit event's comment in non-default translatable locale
    Given I am on the "Events list" page
    And I choose "pl" from translatable locale list
    And I edit first event on the list
    And I change first comment's text to "świetna wiadomość"
    When I press "Save" button
    And I edit first event on the list
    Then I should see one comment with text "świetna wiadomość"
    When I choose "en" from translatable locale list
    Then I should see one comment with text "great news"
