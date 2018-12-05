<?php

trait a { use b, c, d, e;}

trait b { use e;}
trait c { }
trait d { }

trait e { use d, c;}

?>