<?php

$expected     = array('catch (\\Q1 | \\Q2 | \\Q3 | \\Q4 $s) { /**/ } ',
                      'catch (T1 | T2 | T3 $s) { /**/ } ',
                      'catch (D1 | D2 $s) { /**/ } ',
                     );

$expected_not = array('catch (Single\\Nsname $s) { /**/ } ',
                      'catch (Single $s) { /**/ } ',
                     );

?>