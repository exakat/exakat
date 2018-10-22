<?php

new Reflection();
new ReflectionClass();
new ReflectionZendExtension();
new ReflectionExtension();

try {
    new reflection();
    new reflectionClass();
    new reflectionZendExtension();
    new reflectionExtension();
} catch( Exception $e) {}

?>