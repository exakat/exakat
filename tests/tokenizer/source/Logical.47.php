<?php

echo $a | $b->c;
echo $b->c || $d['e'];

echo $a | $b->c || $d['e'];

echo $a | ($b->c || $d['e']);
