<?php

namespace D\E{

use A\B;
//use B;
use C,D;
use H;
use J;

class classAsub extends B\C { }
//class classBfull extends B { }
//class classCDfull extends C,D { }
//class classEFsub extends E\G,F\G { }
//class classHImix extends H\J,J { }
(new classAsub())->go();

}

namespace A\B {
class C {
    function go() {echo __CLASS__;}
}
}
?>
