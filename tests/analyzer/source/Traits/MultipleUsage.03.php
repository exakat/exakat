<?php

trait a { use b, c, d, e;}

trait b { use e;}
trait c { use e;}
trait d { use f;}
trait f { use e;}
trait g { use h;}
trait h { }

trait e { use d, c;}

?>