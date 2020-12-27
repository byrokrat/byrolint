Feature: Using multiple linters

  Scenario: I test a valid plusgiro and bankgiro account number
    Given a fresh installation
    When I run "byrolint 58056201 --bankgiro --plusgiro"
    Then the return code is "0"

  Scenario: I test using all linters
    Given a fresh installation
    When I run "byrolint 1234"
    Then the return code is "1"

  Scenario: I test using all linters in verbose mode
    Given a fresh installation
    When I run "byrolint 58056201 --verbose"
    Then the return code is "1"
    And the output contains "5805620-1"
    And the output contains "5805-6201"
