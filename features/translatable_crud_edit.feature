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
    And I add new file to the news with name "Event en" in "en" locale
    When I am on the "Events list" page
    And I choose "Polish" from translatable locale list
    And I edit first event on the list

  @javascript
  Scenario: Edit form view without translation in non-default translatable locale
    Then I should see form "Name" field with empty value
    And I should see form "Agreement" file field with empty value
    And I should see form "Description" field with empty value
    And I should see form "Comments" field with 1 value
    And I should see form "Files" field with empty value
    And form "Name" field should have translatable flag
    And form "Agreement" field should have translatable flag
    And form "Description" field should have translatable flag
    And form "Name" field should have badge with "en" default locale
    And form "Agreement" field should have badge with "en" default locale
    And form "Description" field should not have badge with default locale

  @javascript
  Scenario: Popovers with default locale text value
    When I click default locale badge for "Name" field
    Then I should see popover with content "Event en"

  @javascript
  Scenario: Popovers with default locale file value
    When I click default locale badge for "Agreement" field
    Then I should see popover with anchor to file

  Scenario: Edit event item in different translatable locale
    When I change "Name" field value to "Event pl"
    And I press "Save" button
    Then I should see following list
      | Name     |
      | Event pl |
    And I choose "English" from translatable locale list
    Then I should see following list
      | Name     |
      | Event en |

  Scenario: Edit event's comment in non-default translatable locale
    When I change first comment's text to "świetna wiadomość"
    And I press "Save" button
    And I edit first event on the list
    Then I should see one comment with text "świetna wiadomość"
    When I choose "English" from translatable locale list
    Then I should see one comment with text "great news"
