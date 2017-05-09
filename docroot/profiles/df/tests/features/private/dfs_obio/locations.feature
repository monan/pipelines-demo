@dfs_obio
Feature: DFS OBIO: Locations
  In order to prove that the location page displays and functions correctly.
  As a developer
  I need to check for store locations.

  @api
  Scenario: Locations: Page
    Given I am on "/locations"
    Then I should see "Our Locations"
    And I should see "San Francisco"
    And I should see "Washington D.C."

  @javascript
  Scenario: Individual location
    Given I am on "/location/san-francisco"
    Then I should see "San Francisco"
    And I should see "Nestled on the edge of the financial district"
    And I click the ".leaflet-marker-icon" element
    Then I should see "466 Geary Street"
