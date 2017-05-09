@dfs_dev @api
Feature: DFS DEV: Homepage
  In order to prove that dfs_dev was enabled correctly
  As a developer
  I need to check for elements on the front page

  Scenario: Block: Example Slideshow
    Given I am on the homepage
    Then I should see "Example Slideshow"
    And I should see "Who likes blocks?"

  Scenario: Block: Example Hero
    Given I am on the homepage
    Then I should see "Example Hero"
    And I should see "We like blocks"

  Scenario: Block: Example Map
    Given I am on the homepage
    Then I should see "Example Map"

  Scenario: Block: Basic
    Given I am on the homepage
    Then I should see "Block vero eos et"
    And I should see "Quis aute Block"

  Scenario: Block: User login
    Given I am on the homepage
    Then I should see "User login"

  Scenario: Block: Powered by Drupal
    Given I am on the homepage
    Then I should see "Powered by Drupal"
