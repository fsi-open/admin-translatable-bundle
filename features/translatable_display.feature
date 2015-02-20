Feature: Display translatable item
  In order to display existing translatable item in specific locale
  As an administrator
  I need to choose this locale from translatable locale list

  Background:
    Given the following admin translatable elements were registered
      | Element Id          | Class                                 |
      | admin_event         | FSi\FixturesBundle\Admin\Event        |
      | admin_event_preview | FSi\FixturesBundle\Admin\EventPreview |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And default translatable locale is "en"
    And I add new event with following values:
      | locale | field | value    |
      | pl     | name  | Event pl |
      | en     | name  | Event en |
      | de     | name  | Event de |

  Scenario: Display event item in different translatable locale
    Given I am on the "Events list" page
    And I choose "Pl" from translatable locale list
    And I display first event on the list
    Then I should see row "Name" with value "Event pl"
    And I choose "En" from translatable locale list
    Then I should see row "Name" with value "Event en"
    And I choose "De" from translatable locale list
    Then I should see row "Name" with value "Event de"
