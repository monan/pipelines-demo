@dfs_obio
Feature: DFS OBIO: Newsletter
  In order to prove that the newsletter displays and functions correctly.
  As a developer
  I need to view and subscribe to the newsletter

  @api
  Scenario: Newsletter: Subscribe
    Given I am on the homepage
    Then I should see "Subscribe & Save"
    When I fill in "edit-email" with "customer@example.com"
    And I press "Sign Up"
    Then I should see "Thanks for signing up!"

  @api
  Scenario: Newsletter: Validation
    Given I am on the homepage
    Then I should see "Subscribe & Save"
    When I fill in "edit-email" with "invalid_address"
    And I press "Sign Up"
    Then I should see "invalid_address is an invalid email address."
    And I should not see "An attempt to send an e-mail message failed."
    And I should not see "Unable to send email. Contact the site administrator if the problem persists."
