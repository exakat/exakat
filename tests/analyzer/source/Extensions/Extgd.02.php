<?php
namespace X {
    function imagesx() { return 1; }
    
    print imagesx(1);
}

namespace {
    imagesx(2);
}
?>