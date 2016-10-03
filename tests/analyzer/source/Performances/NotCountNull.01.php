<?php

if (count($a1) == 0) {}
if (strlen($a2) === 0) {}
if (\count($a3) !== 0) {}
if (\strlen($a4) != 0) {}

if (count($a5) > 0) {}
if (0 > count($a6)) {}

if (count($a7) < 0) {}
if (strlen($a8) >= 0) {}

// OK
if (count($a9) == -1) {}
if (2 === strlen($a10)) {}
if (count($a11) == -1) {}

?>