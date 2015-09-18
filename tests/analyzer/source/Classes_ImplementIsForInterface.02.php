<?php

use \Stagehand\TestRunner\Runner\PHPUnitRunner\Printer\JUnitXMLPrinter as x7;
use \PHPMentors\DomainKata\Service\ServiceInterface as x9;

class x implements \Stdclass {} // Uses a class

class x2 implements \ArrayAccess {} 

// ServiceInterface is a composer interface
class x3 implements \PHPMentors\DomainKata\Service\ServiceInterface {}

// JUnitXMLPrinter is a composer class
class x4 implements \Stagehand\TestRunner\Runner\PHPUnitRunner\Printer\JUnitXMLPrinter {} // Uses a class

class x5 implements x6 {}  // Uses a class

class x6 {} // Not for testing purpose

// x7 is a use

class x8 implements x7 {}// Uses a class

// x9 is a use

class x10 implements x9 {} // Uses an interface

?>