<?php

$expected     = array('class AHelper33 extends AHelper32 { /**/ } ',
                      'class AHelper21 extends \\Zend_View_Helper_Abstract { /**/ } ',
                      'class AHelper31 extends \\Zend_View_Helper_Abstract { /**/ } ',
                      'class AHelper32 extends AHelper31 { /**/ } ',
                      'class AHelper22 extends AHelper21 { /**/ } ',
                      'class AHelper extends Zend_View_Helper_Abstract { /**/ } ',
                     );

$expected_not = array('class NotAHelperWithExtends extends A { /**/ } ',
                      'class NotAHelper { /**/ } ',
                     );

?>