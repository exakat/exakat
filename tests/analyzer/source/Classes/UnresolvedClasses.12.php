<?php

namespace d {
    class c {        function __construct() { print __method__."\n";} }

    new $b[1];
    new $d->e[1];
    new c();
    new d();
}


?>