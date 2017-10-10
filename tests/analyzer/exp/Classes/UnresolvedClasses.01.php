<?php

$expected     = array('bax( )',  // namespace a

                      'abx( )',   // namespace b

                      'abx( )',   // namespace c
                      'bax( )', 

                      'f\\abx( )', // namespace d12
                      'bax( )',
                      );

$expected_not = array('e\abx( )',
                      'a\abx( )',
                      'f\abx()',
                     );

?>