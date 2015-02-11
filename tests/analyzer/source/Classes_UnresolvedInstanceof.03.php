<?php

interface i {}
interface c {}

($traversable instanceof \Traversable2);
($traversable instanceof \Traversable);
($traversable2 instanceof i);
($traversable3 instanceof c);
($traversable2 instanceof i2);
($traversable3 instanceof c2);
($traversable2 instanceof \i3);
($traversable3 instanceof \c3);

?>