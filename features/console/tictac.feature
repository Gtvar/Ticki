@console
Feature: Use tic tac command
  We have some board
  1   2   3
  4   5   6
  7   8   9
  Testing only simple strategy

  Scenario: Win tic tac toe
    When strategy is "simple"
    When I run "tic-tac-toe" command with interactive set: "1,7,8,9"
    Then After finish game i see text "You Win!"
    Then The command exit code should be 0

  Scenario: Lost tic tac toe
    When strategy is "simple"
    When I run "tic-tac-toe" command with interactive set: "4,5,8,9"
    Then After finish game i see text "You Lost!"
    Then The command exit code should be 0

  Scenario: Play tic tac toe but no win lo lost...
    When strategy is "simple"
    When I run "tic-tac-toe" command with interactive set: "2,6,4,7,9"
    Then After finish game i see text "Game finish, but not have winner!"
    Then The command exit code should be 0

  Scenario: Win bisector left
    When strategy is "simple"
    When I run "tic-tac-toe" command with interactive set: "1,5,9"
    Then After finish game i see text "You Win!"
    Then The command exit code should be 0

  Scenario: Win bisector right
    When strategy is "simple"
    When I run "tic-tac-toe" command with interactive set: "3,5,7"
    Then After finish game i see text "You Win!"
    Then The command exit code should be 0

  Scenario: Win vertical line
    When strategy is "simple"
    When I run "tic-tac-toe" command with interactive set: "3,6,9"
    Then After finish game i see text "You Win!"
    Then The command exit code should be 0
