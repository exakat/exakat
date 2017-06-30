<?php


if (substr($a, 0, 4) == "\r\n") {}

if (substr($a, 0, 4) == "\r\n\r\n") {}

// ' do not interpolate \
if (substr($a, 0, 3) == '\r\n\t') {}
if (substr($a, 0, 8) == '\r\n\t\t') {}

?>