<?php

$expected     = array('$this->property->method2( )->method3( )',
                      '$c->d->e',
                     );

$expected_not = array('$this->property->method2()->method4()->method5()',
                      '$this->property->method()',
                      '$f->g->h->i',
                     );

?>