<?php
namespace X {
    function imagesx() { return 1; }
    
    print imagesx(1);
    foo();
}

namespace {
    function foo() {}
    imagesx(2);
}

namespace Y {
    imagesx(3);
}

?>