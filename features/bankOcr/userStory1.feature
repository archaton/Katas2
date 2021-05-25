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

  Scenario: User story 2
    Given there is a valid account number "345882865" with digits:
      """
^ _     _  _  _  _  _  _  _
^ _||_||_ |_||_| _||_||_ |_
^ _|  | _||_||_||_ |_||_| _|

      """

  Scenario: User story 2
    Given there is an invalid account number "345882866" with digits:
      """
^ _     _  _  _  _  _  _  _
^ _||_||_ |_||_| _||_||_ |_
^ _|  | _||_||_||_ |_||_||_|

      """

  Scenario: User story 3
    Given there is output "000000051" with with digits:
      """
^ _  _  _  _  _  _  _  _
^| || || || || || || ||_   |
^|_||_||_||_||_||_||_| _|  |

      """

  Scenario: User story 3
    Given there is output "49006771? ILL" with with digits:
      """
^    _  _  _  _  _  _     _
^|_||_|| || ||_   |  |  | _
^  | _||_||_||_|  |  |  | _|

      """

  Scenario: User story 3
    Given there is output "1234?678? ILL" with with digits:
      """
^    _  _     _  _  _  _  _
^  | _| _||_| _ |_   ||_||_|
^  ||_  _|  | _||_|  ||_| _

      """

  Scenario: User story 3
    Given there is output "457508000" with with digits:
      """
^    _  _  _  _  _  _  _  _
^|_||_   ||_ | ||_|| || || |
^  | _|  | _||_||_||_||_||_|

      """

  Scenario: User story 3
    Given there is output "664371495 ERR" with with digits:
      """
^ _  _     _  _        _  _
^|_ |_ |_| _|  |  ||_||_||_
^|_||_|  | _|  |  |  | _| _|

      """

  Scenario: User story 3
    Given there is output "86110??36 ILL" with with digits:
      """
^ _  _        _        _  _
^|_||_   |  || |   |_| _||_
^|_||_|  |  ||_|  || | _||_|

      """

  Scenario: User story 4.1
    Given guessed output "711111111" with digits:
      """
^
^  |  |  |  |  |  |  |  |  |
^  |  |  |  |  |  |  |  |  |

      """

  Scenario: User story 4.2
    Given guessed output "777777177" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^  |  |  |  |  |  |  |  |  |
^  |  |  |  |  |  |  |  |  |

      """

  Scenario: User story 4.3
    Given guessed output "200800000" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^ _|| || || || || || || || |
^|_ |_||_||_||_||_||_||_||_|

      """

  Scenario: User story 4.4
    Given guessed output "333393333" with digits:
      """
^ _  _  _  _  _  _  _  _  _
^ _| _| _| _| _| _| _| _| _|
^ _| _| _| _| _| _| _| _| _|

      """

  Scenario: User story 4.5
    Given digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_||_||_||_||_||_||_||_||_|
^|_||_||_||_||_||_||_||_||_|

      """
    Then guessed output is "888888888 AMB"
    And possible alternatives are:
      | number |
      | 888886888 |
      | 888888880 |
      | 888888988 |

  Scenario: User story 4.6
    Given digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_ |_ |_ |_ |_ |_ |_ |_ |_
^ _| _| _| _| _| _| _| _| _|

      """
    Then guessed output is "555555555 AMB"
    And possible alternatives are:
      | number |
      | 555655555 |
      | 559555555 |

  Scenario: User story 4.7
    Given digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_ |_ |_ |_ |_ |_ |_ |_ |_
^|_||_||_||_||_||_||_||_||_|

      """
    Then guessed output is "666666666 AMB"
    And possible alternatives are:
      | number |
      | 666566666 |
      | 686666666 |

  Scenario: User story 4.8
    Given digits:
      """
^ _  _  _  _  _  _  _  _  _
^|_||_||_||_||_||_||_||_||_|
^ _| _| _| _| _| _| _| _| _|

      """
    Then guessed output is "999999999 AMB"
    And possible alternatives are:
      | number |
      | 899999999 |
      | 993999999 |
      | 999959999 |

  Scenario: User story 4.9
    Given digits:
      """
^    _  _  _  _  _  _     _
^|_||_|| || ||_   |  |  ||_
^  | _||_||_||_|  |  |  | _|

      """
    Then guessed output is "490067715 AMB"
    And possible alternatives are:
      | number |
      | 490067115 |
      | 490067719 |
      | 490867715 |

  Scenario: User story 4.10
    Given guessed output "123456789" with digits:
      """
^    _  _     _  _  _  _  _
^ _| _| _||_||_ |_   ||_||_|
^  ||_  _|  | _||_|  ||_| _|

      """

  Scenario: User story 4.11
    Given guessed output "000000051" with digits:
      """
^ _     _  _  _  _  _  _
^| || || || || || || ||_   |
^|_||_||_||_||_||_||_| _|  |

      """

  Scenario: User story 4.12
    Given guessed output "490867715" with digits:
      """
^    _  _  _  _  _  _     _
^|_||_|| ||_||_   |  |  | _
^  | _||_||_||_|  |  |  | _|

      """
