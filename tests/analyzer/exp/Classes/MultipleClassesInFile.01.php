<?php

$expected     = array( './tests/analyzer/source/Classes/MultipleClassesInFile.01.php/oneClassoneInterface.php', 
                      './tests/analyzer/source/Classes/MultipleClassesInFile.01.php/twoClasses.php', 
                      './tests/analyzer/source/Classes/MultipleClassesInFile.01.php/threeClasses.php', 
                      './tests/analyzer/source/Classes/MultipleClassesInFile.01.php/oneClassoneTrait.php');

$expected_not = array('./tests/analyzer/source/Classes/MultipleClassesInFile.01.php/oneClassAndAnonymous.php');

?>