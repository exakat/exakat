<?php

preg_match('/'. B . '/', $a, $b);

const A = 'a';
preg_match('/'. A . D . '/', $a, $b);

const C = '[ac-';
preg_match('/'. C . D .'/', $a, $b);

const E = 'dd';
preg_match('/'. C . E .'/', $a, $b);

?>