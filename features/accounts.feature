Feature: Linting account numbers

  Scenario: I test a valid account
    Given a fresh installation
    When I run "byrolint --account 50001111116"
    Then the return code is "0"
    And the output contains "5000000001111116"

  Scenario: I test an invalid account
    Given a fresh installation
    When I run "byrolint --account 58056201"
    Then the return code is "1"

  Scenario: I test a valid plusgiro account
    Given a fresh installation
    When I run "byrolint --plusgiro 58056201"
    Then the return code is "0"
    And the output contains "5805620-1"

  Scenario: I test an invalid plusgiro account
    Given a fresh installation
    When I run "byrolint --plusgiro 50001111116"
    Then the return code is "1"

  Scenario: I test a valid bankgiro account
    Given a fresh installation
    When I run "byrolint --bankgiro 58056201"
    Then the return code is "0"
    And the output contains "5805-6201"

  Scenario: I test an invalid bankgiro account
    Given a fresh installation
    When I run "byrolint --bankgiro 50001111116"
    Then the return code is "1"
