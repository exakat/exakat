<?php

$expected     = array('abstract static public function aspmc( ) ;',
                      'static public abstract function spamc( ) ;',
                     );

$expected_not = array('static public function spmc() { /**/ } ',
                     );

?>