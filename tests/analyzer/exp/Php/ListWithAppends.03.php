<?php

$expected     = array('list($t->a[], $t->a[], $t->a[])',
                      'list($t->a[], $t->b, $t->a[])',
                      'list($t->a[], $t->b[], $t->a[])',
                      'list($t->a[], $t->b[], $t->a[], $t->b[])',
                      );

$expected_not = array('list($t->a[], $t->b[], $t->c[])');

?>