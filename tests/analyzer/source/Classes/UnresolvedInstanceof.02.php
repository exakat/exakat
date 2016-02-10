<?php

interface A {}
class C {}
interface D {}
class D {}
trait TE {}

$b instanceof A;
$b instanceof C;
$b instanceof D;
$b instanceof TE;

?>