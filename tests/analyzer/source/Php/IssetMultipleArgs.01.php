<?php

if (isset($a1) && isset($b)) {}
if (isset($a2) and isset($b)) {}
if (!isset($a3) || !isset($b)) {}

if (isset($a) || isset($b)) {}
if (!isset($a) || isset($b3)) {}
if (isset($a) || isset($b4)) {}
if (isset($a) || !isset($b5)) {}
if (!isset($a) && !isset($b5)) {}


?>