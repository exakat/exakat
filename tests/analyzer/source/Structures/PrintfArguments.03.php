<?php

// No found
sprintf($a, "[" );
sprintf($a->b, "[" );
sprintf($a['b'], "[" );
sprintf($a::B, "[" );

//
sprintf('%f', $a - $b);
sprintf('%f', $a - $b, D);
sprintf('%0.3f', $a - $b);
sprintf('%0.3f', $a - $b, $c);

sprintf('%+f', $a - $b, $c);

sprintf('%1$d monkeys', $a, $b, $c);
sprintf('%04d monkeys', $a, $b, $c);
sprintf('%1$04d monkeys', $a, $b, $c);

sprintf('The %2$s contains %1$04d monkeys', $a, $b, $c);
sprintf('The %2$s contains %1$04d monkeys', $a, $b);
sprintf('The %2$s contains %1$04d monkeys', $a);


sprintf('%02X%02X%02X', $color[0], $color[1], $color[2]);
sprintf("&#%03d;", $i);
sprintf("%03d<", $idx);
?>