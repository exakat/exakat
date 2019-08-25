<?php
sprintf('<a href="http://%1$s">%1$s</a>', $a);
sprintf('<a href="http://%1$s">%1$s</a>', $a, $b);
sprintf('<a href="http://%3$s">%1$s</a>', $a, $b, $c);
sprintf('<a href="http://%s">%1$s</a>', $a, $b, $c);

vprintf('<a href="http://%1$s">%2$s</a>', array($a));
vprintf('<a href="http://%1$s">%2$s</a>', array($a, $b));
vprintf('<a href="http://%1$s">%2$s</a>', array($a, $b, $c));

?>