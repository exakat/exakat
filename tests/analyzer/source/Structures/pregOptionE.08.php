<?php

preg_replace('$(a)$sie', 'b', $c);
preg_replace('*(a)*sie', 'b', $c);
preg_replace('?(a)?sie', 'b', $c);
preg_replace('|(a)|sie', 'b', $c);
preg_replace('+(a)+sie', 'b', $c);
preg_replace('.(a).sie', 'b', $c);

preg_replace('\'(a)\'si', 'b', $c);
preg_replace('\"(a)\"sie', 'b', $c);

?>