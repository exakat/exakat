<?php

preg_match('/(a)(b)/', 'adc', $r);
preg_match('/(a)b?/', 'adc', $r);
print_r($r);
preg_match('/(a)(b)?/', 'adc', $r);
print_r($r);
preg_match('/(a)(b)?(d)/', 'adc', $r);
print_r($r);
preg_match('/(a)(b)?d(c)/', 'adc', $r);
print_r($r);
preg_match('/(a)(b)?d(c)?(d)?/', 'adc', $r);

?>