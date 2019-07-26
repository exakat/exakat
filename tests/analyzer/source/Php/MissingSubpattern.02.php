<?php

preg_replace('/(a)(b)?/', 'adc', $r, $count);
preg_match('/(a)(b)?/', 'adc', $r);
preg_match('/(a)(b)?/', 'adc', $r, PREG_UNMATCHED_AS_NULL);
preg_match('/(a)(b)?/', 'adc', $r, \PREG_UNMATCHED_AS_NULL);
preg_match('/(a)(b)?/', 'adc', $r, \PREG_UNMATCHED_AS_NULL | PREG_OFFSET_CAPTURE);

?>