@df @user @api
Feature: Content reviewer user
  As a content reviewer, I need to be able to review and moderate content.

  Scenario: Content reviewers should not be able to access administration pages.
    Given I am logged in as a user with the "reviewer" role
    When I go to "/admin"
    Then the response status code should be 403

  Scenario: Content reviewers should be able to view lists of site content
    Given I am logged in as a user with the "reviewer" role
    When I visit "/admin/content"
    And I should see the page title "Content"
    And I should see the link "Add content"

  Scenario: Content reviewers should not be able to view lists of site media
    Given I am logged in as a user with the "reviewer" role
    When I go to "/admin/content/media"
    Then the response status code should be 403
