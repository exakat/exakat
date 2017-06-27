<?php

$expected     = array('$this', // 5 times, yes.
                      '$this',
                      '$this',
                      '$this',
                      '$this',
                      );

$expected_not = array('$this' ); // 6th and 7th are inside a class and a trait

?>