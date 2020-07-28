<?php

if (fooBoolean() === true) {}
if (fooBoolean() == true) {}

if (fooBoolean() == 1) {}
if (fooInt() === true) {}

if (fooInt() === 1) {}
if (fooInt() == 1) {}

function fooBoolean() : bool { return true; }
function fooInt() : int { return 1; }
?>