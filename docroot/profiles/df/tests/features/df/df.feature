@none @api
Feature: Demo Framework
  In order to prove that general features for a given scenario function properly
  As a developer
  I need to perform various basic tests

  Scenario: Homepage (anon)
    Given I am on the homepage
    Then the response status code should be 200

  Scenario: Homepage (admin)
    Given I am logged in as a user with the "administrator" role
    And I am on the homepage
    Then the response status code should be 200

  Scenario: Confirm anonymous user
    Given I am on "/user"
    Then I should see the heading "Log in"
    And I should see "Username"
    And I should see "Password"

  Scenario: Confirm authenticated user
    Given I am logged in as a user with the "administrator" role
    And I am on "/user"
    Then I should see the heading "My account"
    And I should see "Member for"

  Scenario: Ensure that database logging is available
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/reports/dblog"
    Then I should see "Recent log messages"

  Scenario: Ensure that the update module is disabled by default
    Given I am logged in as a user with the "administrator" role
    And I am on "/admin/modules"
    Then the "Update Manager" checkbox should not be checked
