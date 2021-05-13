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
^ _
^| |
^|_|
      """

  Scenario: Single digit
    Given there is a single number "1" with digit:
      """
^
^  |
^  |
      """

  Scenario: Single digit
    Given there is a single number "2" with digit:
      """
^ _
^ _|
^|_
      """

  Scenario: Single digit
    Given there is a single number "3" with digit:
      """
^ _
^ _|
^ _|
      """

  Scenario: Single digit
    Given there is a single number "4" with digit:
      """
^
^|_|
^  |
      """

  Scenario: Single digit
    Given there is a single number "5" with digit:
      """
^ _
^|_
^ _|
      """

  Scenario: Single digit
    Given there is a single number "6" with digit:
      """
^ _
^|_
^|_|
      """

  Scenario: Single digit
    Given there is a single number "7" with digit:
      """
^ _
^  |
^  |
      """

  Scenario: Single digit
    Given there is a single number "8" with digit:
      """
^ _
^|_|
^|_|
      """

  Scenario: Single digit
    Given there is a single number "9" with digit:
      """
^ _
^|_|
^ _|
      """

  Scenario: User story 1.0
    Given there is number "000000000" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^| || || || || || || || || |
^|_||_||_||_||_||_||_||_||_|

      """

  Scenario: User story 1.1
    Given there is number "111111111" with digits:
      """
^
^  |  |  |  |  |  |  |  |  |
^  |  |  |  |  |  |  |  |  |

      """

  Scenario: User story 1.2
    Given there is number "222222222" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^ _| _| _| _| _| _| _| _| _|
^|_ |_ |_ |_ |_ |_ |_ |_ |_

      """

  Scenario: User story 1.2
    Given there is number "333333333" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^ _| _| _| _| _| _| _| _| _|
^ _| _| _| _| _| _| _| _| _|

      """

  Scenario: User story
    Given there is number "444444444" with digits:
      """
^
^|_||_||_||_||_||_||_||_||_|
^  |  |  |  |  |  |  |  |  |

      """

  Scenario: User story 1.2
    Given there is number "555555555" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_ |_ |_ |_ |_ |_ |_ |_ |_
^ _| _| _| _| _| _| _| _| _|

      """

  Scenario: User story
    Given there is number "666666666" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_ |_ |_ |_ |_ |_ |_ |_ |_
^|_||_||_||_||_||_||_||_||_|

      """

  Scenario: User story
    Given there is number "777777777" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^  |  |  |  |  |  |  |  |  |
^  |  |  |  |  |  |  |  |  |

      """

  Scenario: User story
    Given there is number "888888888" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_||_||_||_||_||_||_||_||_|
^|_||_||_||_||_||_||_||_||_|

      """

  Scenario: User story
    Given there is number "999999999" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_||_||_||_||_||_||_||_||_|
^ _| _| _| _| _| _| _| _| _|

      """

  Scenario: User story
    Given there is number "123456789" with digits:
      """
^    _  _     _  _  _  _  _
^  | _| _||_||_ |_   ||_||_|
^  ||_  _|  | _||_|  ||_| _|

      """
