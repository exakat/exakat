<?php

fopen('a', 'r');
fopen('b', 'w+');
\fopen('c', 'xb+');
\fopen('c', 'x+b');
\fopen('d', 't');
fopen('g', 'h');
\fopen('i', 'c+');
\b\fopen('j', 'ce+');

$object->fopen('e', 'f');
classe::fopen('e', 'f');
fopen('k', 'a'.'+');
fopen('l', "$a+");

fopen('rw', 'rw');

?>