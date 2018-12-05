<?php

trait A { use A;}

trait B1 { use b2;}
trait b2 { use b1;}

trait c1 { use c2;}
trait c2 { use c3;}
trait c3 { use c1;}

trait d { use e;}
trait e { }

trait f1 { use f2;}
trait f2 { use f3;}
trait f3 { use f4;}
trait f4 { }

trait g1 { use g2, h;}
trait g2 { use g3, h;}
trait g3 { use g1, h;}
trait h { }


?>