<?php

$expected     = array('Zend\\Authentication\\Adapter\\Callback',
                     );

$expected_not = array('Zend\\Authentication\\Adapter\\DbTable\\AbstractAdapter',
                      'Not\\Zend\\Authentication\\Adapter\\DbTable\\AbstractAdapter',
                      'Not\\Zend\\Authentication\\Adapter\\Callback',
                     );

?>