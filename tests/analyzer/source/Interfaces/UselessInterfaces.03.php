<?php

interface usedInterface {}
interface unusedInterface {}
interface unusedInterface2 {}

// useless, as it only extends another extension
interface a extends usedInterface2{}
function ($x) : usedInterface {};

?>