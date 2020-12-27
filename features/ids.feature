Feature: Linting id numbers

  Scenario: I test a vaild personal id number
    Given a fresh installation
    When I run "byrolint --personal-id 9403232383"
    Then the return code is "0"
    And the output contains "940323-2383"
    And the output contains "199403232383"

  Scenario: I test an invalid personal id number
    Given a fresh installation
    When I run "byrolint --personal-id 58056201"
    Then the return code is "1"

  Scenario: I test a valid coordination id number
    Given a fresh installation
    When I run "byrolint --coordination-id 7010632391"
    Then the return code is "0"
    And the output contains "701063-2391"
    And the output contains "197010632391"

  Scenario: I test an invalid coordination id number
    Given a fresh installation
    When I run "byrolint --coordination-id 7010632392"
    Then the return code is "1"

  Scenario: I test a valid organization id number
    Given a fresh installation
    When I run "byrolint --organization-id 5020177753"
    Then the return code is "0"
    And the output contains "502017-7753"
    And the output contains "005020177753"

  Scenario: I test an invalid organization id number
    Given a fresh installation
    When I run "byrolint --organization-id 9403232383"
    Then the return code is "1"
