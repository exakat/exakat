<?php

preg_replace('\'(a)\'si', 'b', $c);
preg_replace("\"(a)\"sieK", 'b', $c);

preg_replace('/(a)('.$kw.')(b)/sie', 'c'.$d.'e', $data);
preg_replace('/(a)('.$kw.')(b)/siK', 'c'.$d.'e', $data);

?>