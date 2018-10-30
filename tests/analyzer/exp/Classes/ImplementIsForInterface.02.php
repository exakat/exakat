<?php

$expected     = array('class x implements \\Stdclass { /**/ } ',
                      'class x4 implements \\Stagehand\\TestRunner\\Runner\\PHPUnitRunner\\Printer\\JUnitXMLPrinter { /**/ } ',
                      'class x5 implements x6 { /**/ } ',
                     );

$expected_not = array('class x2 implements \\ArrayAccess { /**/ }',
                      'class x3 implements \\PHPMentors\\DomainKata\\Service\\ServiceInterface { /**/ }',
                      'class x10 implements x9 { /**/ }',
                      'class x8 implements x7 { /**/ } ',
                     );

?>