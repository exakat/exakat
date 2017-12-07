<?php

$expected     = array('SoapClient("some.wsdl")',
                      'SoapHeader( )',
                      'SoapClient(null, array(\'location\' => "http://localhost/soap.php", \'uri\' => "http://test-uri/"))',
                     );

$expected_not = array(
                     );

?>