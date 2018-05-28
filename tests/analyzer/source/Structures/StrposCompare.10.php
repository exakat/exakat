<?php

if (preg_match('/asdf/', $a, $b)) {}
if (preg_match('/'.$s.'/', $a, $b)) {}
if (preg_match('/'.foo().'/', $a, $b)) {}
if (preg_match('/'.A::foo().'/', $a, $b)) {}
if (preg_match('/'.$b->foo().'/', $a, $b)) {}
if (preg_match('/'.$s[2].'/', $a, $b)) {}
if (preg_match('/'.$s->d.'/', $a, $b)) {}
if (preg_match_nope("/$s/", $a, $b)) {}

?>