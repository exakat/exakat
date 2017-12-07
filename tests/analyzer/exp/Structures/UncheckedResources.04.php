<?php

$expected     = array('fread(fopen($d, \'r\'))',
                     );

$expected_not = array('A\\B\\C::fread(fopen($d,\'r\'))',
                      '$a->fread(fopen($e,\'w\'))',
                      'read(fopen($d,\'r\'))',
                      'A\\B\\C::read(fopen($d,\'r\'))',
                      '$a->read(fopen($e,\'w\')',
                     );

?>