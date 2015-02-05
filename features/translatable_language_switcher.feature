Feature: Translatable locale list appearance
  In order to show translatable locale list
  As a developer
  I need to install FSiAdminTranslatableBundle and define locales

  Background:
    Given the following admin translatable elements were registered
      | Element Id  | Class                          |
      | admin_event | FSi\FixturesBundle\Admin\Event |
    And the following admin non-translatable elements were registered
      | Element Id    | Class                           |
      | admin_news    | FSi\FixturesBundle\Admin\News   |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |

  Scenario: Translatable locale list appearance
    Given I am on the "Events list" page
    Then I should see translatable locale list
    And translatable locale list should have following locales
      | Locale    |
      | en        |
      | pl        |
      | de        |

  Scenario: Translatable locale list is inactive for non-translatable elements
    Given I am on the "News list" page
    Then I should see translatable locale list
    And translatable locale list should be inactive
