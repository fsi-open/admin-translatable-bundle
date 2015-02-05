Feature: Add new entity
  Background:
    Given the following admin translatable elements were registered
      | Element Id    | Class                                |
      | admin_form    | FSi\FixturesBundle\Admin\Form |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And default translatable locale is "en"

  Scenario: Adding comment
    Given I am on the "New comment" page
    And I choose "pl" from translatable locale list
    And I change "Text" field value to "Comment text pl"
    When I press "Save" button
    Then It should be saved comment entity with text "Comment text pl"
