<?php

trait a { use b, c, d, e;}

trait b { use e;}
trait c { use e;}
trait d { }

trait e { use d, c;}

?>