<?php

class a extends \Exception {}
class unthrownA extends \RuntimeException {}

class b extends \RuntimeException {}
class unthrownB extends \RuntimeException {}

class c {} 

class d extends e {}

function x() {
    throw new a('test');
    throw new \b('test');
    throw new d(); // impossible, though ...
    
}

?>