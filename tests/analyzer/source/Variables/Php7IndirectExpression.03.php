<?php

class A {
    public $expect = ['a' => 1];
}

$a = new A();
$source = 'a';
$expect = 'expect';

echo $a->{$expect}[$source];
echo $a->$expect[$source];

?>