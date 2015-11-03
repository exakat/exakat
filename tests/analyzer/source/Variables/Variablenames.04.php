<?php

$dynamicFunction();

$object = new StdClass();
$object->$dynamicMethod(1);

aClass::$staticProperty = 2;

aClass::$dynamicStaticMethod(3);


?>