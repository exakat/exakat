<?php

$expected     = array('Zend_Acl_Exception',
                      'Zend_Math_BigInteger_Exception',
                      'Zend_Http_UserAgent_Features_Adapter_WurflApi',
                      'Zend\\Authentication\\Adapter\\Http\\Exception\\ExceptionInterface',
                      'Zend\\Authentication\\Adapter\\Http\\ResolverInterface',
                     );

$expected_not = array('A\\Zend_Acl_Exception',
                      'A\\Zend_Math_BigInteger_Exception',
                      'A\\Zend_Http_UserAgent_Features_Adapter_WurflApi',
                      'Not\\Zend\\Authentication\\Adapter\\Http\\Exception\\ExceptionInterface',
                      'Not\\Zend\\Authentication\\Adapter\\Http\\ResolverInterface',
                     );

?>