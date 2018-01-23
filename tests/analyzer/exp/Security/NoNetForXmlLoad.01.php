<?php

$expected     = array('$dom->loadXml($uri)',
                      '$dom->loadXml($uri, LIBXML_NOENT)',
                      '$dom->loadXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING)',
                      '$dom->loadXml($uri, LIBXML_NOENT)',
                      '$dom->loadXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | B | C)',
                      '$dom->loadXml($uri, 33)',
                     );

$expected_not = array('$dom->loadXml($uri, LIBXML_NONET)',
                      '$dom->loadXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | LIBXML_NONET | C)',
                      '$dom->loadXml($uri, $options)',
                     );

?>