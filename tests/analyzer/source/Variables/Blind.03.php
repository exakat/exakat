<?php

class A { public static $staticVariableA; }

foreach($a as $b => A::$staticVariable) {

}

class D { public static $staticVariableD; }

foreach($d as D::$staticVariableD) {

}

?>