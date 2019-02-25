<?php

$expected     = array('$i->h( )', 
                      '$this->b()->d()',
                     );

$expected_not = array('$e->getMessage( )', 
                      '$this->foo2( )',
                      '$a->b( )',
                      '$this->c->ds( )',
                      '$g->h( )', 
                      '$GLOBALS[\'a\']->foo( )',
                     );

?>