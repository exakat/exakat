<?php

$expected     = array('C::$P',
                     );

$expected_not = array('C::$p',
                      'get_class($this->resource)',
                     );

?>