@dfs_tec @api
Feature: DFS TEC: Homepage
  In order to prove that dfs_tec was enabled correctly
  As a developer
  I need to check for elements on the front page

  Scenario: Block: Slideshow
    Given I am on the homepage
    Then I should see "Insight that makes sense of it all"
    And I should see "Strong providers in innovation."
    And I should see "The perspective that affects your bottom line."

  Scenario: Block: Services Promo
    Given I am on the homepage
    Then I should see "Bayside Consulting is your global business consulting partner."
    And I should see "Digital Consulting"
    And I should see "Business Transformation"
    And I should see "Research Analysis"

  Scenario: Block: Recent Article
    Given I am on the homepage
    Then I should see "Latest articles"
    And I should see "Planning for success"
    And I should see "The Lost Art of Creativity"
    And I should see "Scalable Personalization"

  Scenario: Block: Footer contact
    Given I am on the homepage
    Then I should see "Address"
    And I should see "Press/PR"
    And I should see "Social"
