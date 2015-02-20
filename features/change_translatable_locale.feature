Feature: Change translatable locale
  In order to change translatable locale
  As a administrator
  I need to choose translatable locale from list

  Background:
    Given the following admin translatable elements were registered
      | Element Id  | Class                          |
      | admin_event | FSi\FixturesBundle\Admin\Event |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |

    Scenario: Change translatable locale
      Given I am on the "Events list" page
      When I choose "De" from translatable locale list
      Then I should see translatable list with "De" option selected
