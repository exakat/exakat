<?php

$expected     = array('Lexer',
                      'Token',
                      'Token',
                      'LexerException(\'Unknown token "\' . $tok->value . \'" at offset \' . $tok->offset . \'.\')',
                     );

$expected_not = array('getToken',
                     );

?>