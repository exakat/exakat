<?php

use A\B\C\D;
use A\B\C\D as A;
use A\B\C\D as Unused;

try {
    doSomething();
} catch (D $e) {

} catch (A $e) {

}
/*

?>