<?php

interface A {}
class C {}
interface Di {}
class Dc {}
trait TE {}

$b instanceof A;
$b instanceof C;
$b instanceof Di;
$b instanceof Dc;
$b instanceof TE;
$b instanceof noCIT;

?>