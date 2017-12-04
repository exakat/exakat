<?php

$expected     = array('class foo2 extends \\Zend\\Mvc\\Controller\\AbstractActionController { /**/ } ',
                      'class foo3 extends foo { /**/ } ',
                      'class foo4 extends foo3 { /**/ } ',
                      'class foo extends AbstractActionController { /**/ } ',
                     );

$expected_not = array('class bar extends NotZend\\Mvc\\Controller\\AbstractActionController { /**/ } ',
                     );

?>