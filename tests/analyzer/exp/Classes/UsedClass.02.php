<?php

$expected     = array('class UsedInArrayWithArrayLowercase { /**/ } ', 
                      'class UsedInArrayWithArray { /**/ } ',
                      'class UsedInArrayWithBracket { /**/ } ',
                      'class UsedInArrayWithBracketLowercase { /**/ } ',
                      'class UsedInString { /**/ } ',
                      'class UsedInStringLowercase { /**/ } ',
                      );

$expected_not = array('class Unused { /**/ } ',
                     );

?>