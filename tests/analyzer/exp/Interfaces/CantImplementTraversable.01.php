<?php

$expected     = array('class a implements z { /**/ } ',
                      'interface i extends Traversable { /**/ } ',
                      'class y implements \\traversable { /**/ } ',
                      'abstract class b2 implements \\Iterator, traversable { /**/ } ',
                     );

$expected_not = array('abstract class b implements \\Iterator { /**/ } ',
                     );

?>