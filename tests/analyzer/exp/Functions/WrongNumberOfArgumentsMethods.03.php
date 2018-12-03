<?php

$expected     = array('$this->Bar(1)',
                      '$this->Bar(1, 2, 3)',
                     );

$expected_not = array('$this->Bar(1, 2)',
                      '$this->BarFunc_get_arg(1)',
                      '$this->BarFunc_get_arg(1, 2)',
                      '$this->BarFunc_get_arg(1, 2, 3)',
                      '$this->BarEllipsis(1)',
                      '$this->BarEllipsis(1, 2)',
                      '$this->BarEllipsis(1, 2, 3)',
                     );

?>