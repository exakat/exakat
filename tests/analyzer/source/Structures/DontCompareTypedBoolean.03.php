<?php

if (fooBoolean() === true) {}
if (fooBoolean() == true) {}
if (fooBoolean2() === true) {}
if (fooBoolean2() == true) {}
if (fooBoolean3() === true) {}
if (fooBoolean3() == true) {}

if (fooBoolean() == 1) {}
if (fooInt() === true) {}

if (fooInt() === 1) {}
if (fooInt() == 1) {}

function fooBoolean() : bool|int { return true; }
function fooBoolean2() : bool|A\B { return true; }
function fooBoolean3() : bool { return true; }
function fooInt() : int { return 1; }
?>