<?php

clone $x;
clone $x[1];
clone $x->d;
clone true;
clone array('x');
clone x;


clone new Stdclass;
clone fooX();
clone $a->fooX();

?>