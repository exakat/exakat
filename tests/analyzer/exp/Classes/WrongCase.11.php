<?php

$expected     = array('use A\\WrongCASE',
                      'use A\\WrongCASEAliased as Alias',
                     );

$expected_not = array('use A\\CorrectCase',
                      'use A\\CorrectCaseAliased as Alias2',
                     );

?>