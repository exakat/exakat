<?php

if ($a) { } elseif ($a || $b) {}
if ($b) { } elseif ($a || $b) {}

if ($a || $b) { } elseif ($a) {}
if ($a OR $b) { } elseif ($b) {}
if ($a or $b) { } elseif ($b || $a) {}
if ($b || $a) { } elseif ($a || $b) {}

if ($a) { } elseif ($b) {}
if ($a && $b) { } elseif ($a) {}


?>