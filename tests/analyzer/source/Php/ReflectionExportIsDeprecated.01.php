<?php
ReflectionFunction::export('foo');
// same as
echo new ReflectionFunction('foo'), "\n";
 
$str = ReflectionFunction::export('foo', true);
// same as
$str = (string) new ReflectionFunction('foo');

$str = (string) new ReflectionNotValid('foo');
$str = (new ReflectionFunction('foo'))->export('foo', true);
?>