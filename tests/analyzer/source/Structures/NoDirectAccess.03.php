<?php

if (defined('A')) return;
if (defined('B')) return null;
if (defined('C')) { return null; }

if (defined('D')) { $a++; }

?>