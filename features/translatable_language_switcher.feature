Feature: Switch to select the language translations
  In order to operate on languages in admin panel
  As a developer
  I need to install FSiAdminTranslatableBundle and set languages

  Background:
    Given the following services were registered
      | Id                           | Class                           | Tag           |
      | fixtures_bundle.admin.events | FSi\FixturesBundle\Admin\Events | admin.element |
    And the following languages were defined
      | Language  |
      | en        |
      | pl        |
      | de        |

  Scenario: Admin panel translatable switcher appearance
    Given I am on the "Events list" page
    Then I should see translatable switcher
    And translatable switcher should have three options

  Scenario: Switcher is active only for translatable elements
    Given I am on the "Admin panel" page
    Then I should see translatable switcher
    And translatable switcher should be inactive
