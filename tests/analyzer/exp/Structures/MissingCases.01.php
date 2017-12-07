<?php

$expected     = array('switch ($a) { /**/ } ',
                      'switch ($o->p) { /**/ } ',
                     );

$expected_not = array('switch (C::$P1) { /**/ } ',
                      'switch (C::$P2) { /**/ } ',
                      'switch (C::$P3) { /**/ } ',
                     );

?>