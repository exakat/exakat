<?php

$expected     = array('new SimpleXml($uri, LIBXML_NOENT)',
                      'new SimpleXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING)',
                      'new SimpleXml($uri, LIBXML_NOENT)',
                      'new SimpleXml($uri, 33)',
                      'new SimpleXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | B | C)',
                      'new SimpleXml($uri)',
                     );

$expected_not = array('new SimpleXml($uri, $options)',
                      'new SimpleXml($uri, LIBXML_NONET)',
                     );

?>