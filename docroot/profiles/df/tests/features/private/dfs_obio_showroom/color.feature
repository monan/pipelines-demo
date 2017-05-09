@dfs_obio_showroom
Feature: DFS OBIO Showroom: Color
  In order to prove that the OBIO theme is recolorable.
  As a developer
  I need to check for page elements.

  @api @javascript
  Scenario: Color: Recolorable
    Given I am logged in as a user with the administrator role
    And I visit "/admin/appearance/settings/obio"
    Then I should see "Color scheme"
    And I should see a "Color set" field
    When I select "obio_brown" from "edit-scheme"
    And I press "Save configuration"
    And I am on the homepage
    Then I should see the ".field-name-field-hero-link a" element with the color "rgb(203, 127, 52)"
    When I visit "/admin/appearance/settings/obio"
    And I select "default" from "edit-scheme"
    And I press "Save configuration"
    And I am on the homepage
    Then I should see the ".field-name-field-hero-link a" element with the color "rgb(51, 102, 204)"
