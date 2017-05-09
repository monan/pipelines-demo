@dfs_obio
Feature: DFS OBIO: Inspiration
  In order to prove that the inspiration page displays and functions correctly.
  As a developer
  I need to check for page elements.

  @api
  Scenario: Inspiration: Page
    Given I am on "/inspiration"
    Then I should see "The Obio Inspiration Blog"

  @api
  Scenario: Inspiration: Search
    Given I am logged in as a user with the administrator role
    And I am on "/admin/config/search/pages"
    When I press "edit-wipe"
    Then I should see "This will re-index content in the search indexes of all active search pages."
    And I press "edit-submit"
    Then I should see "All search indexes will be rebuilt."
    When I run cron
    And I am on "/inspiration"
    Then I fill in "edit-keys" with "Meet"
    And I press "edit-submit"
    Then I should not see "Your search yielded no results."
    And I should see "Search results"
    And I should see "Vintage Collection"

  @api
  Scenario: Inspiration: Filter
    Given I am on "/inspiration"
    When I fill in "edit-field-tags-target-id" with "casual"
    And I press "edit-submit-article-collection"
    And I should see "Meet Shawn and See How Casual Collection Worked for His Business"
    And I should not see "3 Reasons to Never Get Complacent about Your Office's Design"
