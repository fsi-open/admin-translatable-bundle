Feature: List of comment items
  In order to see list of translatable comment items in different locales
  As an administrator
  I can change translatable locale on the list page

  Background:
    Given the following admin translatable elements were registered
      | Element Id    | Class                                |
      | admin_comment | FSi\FixturesBundle\Admin\CommentList |
    And the following translatable locales were defined
      | Locale    |
      | en        |
      | pl        |
      | de        |
    And default translatable locale is "en"
    And there are 5 comments in each locale

  Scenario: Changing the translatable locale for comment list
    Given I am on the "Comments list" page
    And I choose "Polish" from translatable locale list
    And I should see list
      | Text              |
      | Comment text pl 1 |
      | Comment text pl 2 |
      | Comment text pl 3 |
      | Comment text pl 4 |
      | Comment text pl 5 |
    When I choose "English" from translatable locale list
    Then I should see list
      | Text              |
      | Comment text en 1 |
      | Comment text en 2 |
      | Comment text en 3 |
      | Comment text en 4 |
      | Comment text en 5 |
