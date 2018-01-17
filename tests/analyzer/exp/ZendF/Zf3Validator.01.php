<?php

$expected     = array('EmailAddress( )',
                      'Zend\\Validator\\Uuid( )',
                      'Zend\\Validator\\Module( )',
                      'Zend\\Validator\\Isbn\\Isbn10( )',
                     );

$expected_not = array('Zend\\Validator\\EmailAddress',
                      'Zend\\Validator\\NotZend( )',
                     );

?>