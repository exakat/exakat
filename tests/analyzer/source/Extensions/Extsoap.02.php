<?php
//$client = new SoapClient(null,array('location' => "http://localhost/soap.php",
//                                    'uri'      => "http://test-uri/"));
$client->SomeFunction(new SoapParam($a, "a"),
                      new SoapParam($b, "b"),
                      new SoapParam($c, "c"),
                      new RestParam($d, "d"),
                      );
?>