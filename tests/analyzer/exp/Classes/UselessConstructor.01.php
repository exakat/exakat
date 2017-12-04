<?php

$expected     = array('class a { /**/ } ',
                      'class bb extends b { /**/ } ',
                      'class cbb extends cb { /**/ } ',
                     );

$expected_not = array('class ab extends a  { /**/ } ',
                      'class d { /**/ } ',
                      'class dd extends d { /**/ } ',
                     );

?>