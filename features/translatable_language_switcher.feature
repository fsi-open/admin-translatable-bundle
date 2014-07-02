Feature: Switch to select the language translations
  In order to operate on languages in admin panel
  As a developer
  I need to install FSiAdminTranslatableBundle and set languages

  Background:
    Given the following admin translatable elements were registered
      | Service Id                    | Class                           |
      | fixtures_bundle.admin.events  | FSi\FixturesBundle\Admin\Events |
    And the following admin non-translatable elements were registered
      | Service Id                    | Class                           |
      | fixtures_bundle.admin.news  | FSi\FixturesBundle\Admin\News     |
    And the following languages were defined
      | Language  |
      | en        |
      | pl        |
      | de        |

  Scenario: Admin panel translatable switcher appearance
    Given I am on the "Events list" page
    Then I should see translatable switcher on the "Events list" page
    And translatable switcher should have three options on the "Events list" page

  Scenario: Switcher is inactive for non-translatable elements
    Given I am on the "News list" page
    Then I should see translatable switcher on the "News list" page
    And translatable switcher should be inactive on the "News list" page
