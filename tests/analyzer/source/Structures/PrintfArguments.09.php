<?php

const A1 = array("A");
const A2 = array("A", '$b');
const A3 = array("A", '$b', '$c');

vsprintf('<a href="http://%1$s">%2$s</a>', array($a, $b));
vsprintf('<a href="http://%1$s">%2$s</a>', A1);
vsprintf('<a href="http://%1$s">%2$s</a>', A2);
vsprintf('<a href="http://%1$s">%2$s</a>', A3);

?>