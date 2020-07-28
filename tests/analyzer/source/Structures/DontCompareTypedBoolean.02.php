<?php

if (fooBoolean() === true) {}
if (fooBoolean() == true) {}
if (fooBooleanNull() === true) {}
if (fooBooleanNull() == true) {}

if (fooBoolean() == 1) {}
if (fooInt() === true) {}

if (fooInt() === 1) {}
if (fooInt() == 1) {}

function fooBoolean() : ?bool { return true; }
function fooBooleanNull() : bool { return true; }
function fooInt() : ?int { return 1; }
?>