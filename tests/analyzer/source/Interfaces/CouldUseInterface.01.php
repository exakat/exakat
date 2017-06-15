<?php

interface i {
    function i(); 
}

// i is not implemented and declared
class foo {
    function i() {}
    function j() {}
}

// i is implemented and declared
class foo2 implements i {
    function i() {}
    function j() {}
}

?>