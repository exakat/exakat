<?php

class a {
     private $axp = 1;
     protected $axpr = 1;
     public $axpu = 1;

     static private $saxp = 1;
     static protected $saxpr = 1;
     static public $saxpu = 1;

     private function amp() {}
     protected function ampr() {}
     public function ampu() {}

     static private function asmp() {}
     static protected function asmpr() {}
     static public function asmpu() {}
}

$b = new a();
$b->$axp = 2;
$b->$axpr = 2;
$b->$axpu = 2;

$b->amp();
$b->ampr();
$b->ampu();

a::$saxp = 2;
a::$saxpr = 2;
a::$saxpu = 2;

a::asmp();
a::asmpr();
a::asmpu();

?>