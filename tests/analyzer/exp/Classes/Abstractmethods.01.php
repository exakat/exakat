<?php

$expected     = array('abstract public function BmethodAbstract( ) ;',
                      'public abstract function BmethodAbstract2( ) ;',
                      'abstract public function AmethodAbstract( ) ;',
                      'public abstract function AmethodAbstract2( ) ;',
                     );

$expected_not = array('public function BmethodNonAbstract() { /**/ } ',
                     );

?>