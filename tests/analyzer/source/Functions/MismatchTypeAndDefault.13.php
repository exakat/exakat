<?php
function a1(int $flags = \GLOB_NOSORT) : array { }

function a2(int $flags = GLOB_NOSORT) : array {}

const YES = true;
function a3(int $flags = YES) : array {}

?>