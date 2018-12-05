<?php

trait a { use b, c, d, e;}

trait b { }
trait c { }
trait d { use f;}
trait f { use e;}

trait e { use d, c;}

?>