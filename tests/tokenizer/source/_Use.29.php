<?php 

use function  FooLibrary\Bar\Baz\ClassNonAbsNonGroupedNonAs;
use function \FooLibrary\Bar\Baz\ClassAbsNonGroupedNonAs;
use function  FooLibrary\Bar\Baz\ClassNonAbsNonGroupedAs as A1;
use function \FooLibrary\Bar\Baz\ClassAbsNonGroupedAs as A2;
use function  FooLibrary\Bar\Baz\{ ClassNonAbsGroupedNonAs, ClassNonAbsGroupedAs as A3 };

?>