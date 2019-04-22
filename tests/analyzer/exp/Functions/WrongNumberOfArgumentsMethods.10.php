<?php

$expected     = array('$this->selfp->a( )',
                      '$this->selfp->c( )',
                      '$this->selfp->c(5)',
                      '$this->staticp->a( )',
                      '$this->staticp->c( )',
                      '$this->staticp->c(5)',
                      '$this->xp->a( )',
                      '$this->xp->c( )',
                      '$this->xp->c(5)',
                     );

$expected_not = array('$this->selfp->a(1)',
                      '$this->selfp->a(1, 2)',
                      '$this->selfp->c(1, 2)',
                      '$this->staticp->a(1)',
                      '$this->staticp->a(1, 2)',
                      '$this->staticp->c(1, 2)',
                      '$this->xp->a(1)',
                      '$this->xp->a(1, 2)',
                      '$this->xp->c(1, 2)',
                     );

?>