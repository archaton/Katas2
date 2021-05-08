Feature: Scan paper documents
  In order to read letters and faxes
  As a bank worker
  The machine needs to read documents

  Rules:
  - each entry is 4 lines long
  - each line has 27 characters
  - the first 3 lines contain " ", "|" and "_"'s
  - the 4th line is blank
  - each account number should have 9 digits
  - digits in range 0-9
  - a normal file contains around 500 entries

  Scenario: Single digit
    Given there is a single number "0" with digit:
      """
^ _ $
^| |$
^|_|$
      """

  Scenario: User story 1
    Given there is number "000000000" with digits:
      """
^ _  _  _  _  _  _  _  _  _ $
^| || || || || || || || || |$
^|_||_||_||_||_||_||_||_||_|$

      """
