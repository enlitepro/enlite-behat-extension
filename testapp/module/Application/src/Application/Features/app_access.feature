Feature: Application access
  In order to have access to real data
  As a features tester
  I need to have access to the mvc application

  Scenario: Reading and setting application parameters
    Given I have an application instance
    When I get container parameters from it
    Then there should be "test" parameter
    And it should be set to "abc" value
    But there should not be "not-exists" parameter
