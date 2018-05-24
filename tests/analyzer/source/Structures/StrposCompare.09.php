<?php

while (($file = readdir($dh1))) {}
while (($file = readdir($dh2)) != false) {}
while (($file = readdir($dh3)) !== false) {}
while (($file = readdir($dh4)) !== null) {}

?>