<?php

function a_b_dist() {}
charger_fonction('b', 'a');

function c_D_dist() {}
charger_fonction('C', 'd');

function exec_b2_dist() {}
charger_fonction('b2');

function exec_b3_b4_dist() {}
charger_fonction('b3/b4');

function c_b2_dist() {}
charger_fonction('non_defined');
blabla('c_b2');

function someFunction() {}


?>