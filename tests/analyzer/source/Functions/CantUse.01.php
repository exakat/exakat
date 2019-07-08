<?php

Function foo1() { throw new exception(); }
Function foo() { trigger_error(); }
Function bar() { foo(); }
Function bar1() { foo(); return; }

class x {
    Function cbar() { foo(); }

}

trait t {
    Function tbar() { foo(); }

}

?>