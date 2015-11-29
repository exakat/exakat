<?php

namespace {
    class one_identifier {
        const a = 2;
    }
    class absolute_path {
        const a = 2;
    }
    class one_identifier3 {
        const a = 2;
    }

    class a1 {
        const a = 2;
    }
}

namespace complex {
    class path {
        const a = 2;
    }
}

namespace absolute\complex {
    class path2 {
        const a = 2;
    }
}

namespace x {
    use one_identifier;
    use \absolute_path;
    use complex\path;
    use \absolute\complex\path2;

    use one_identifier3 as a1;
    use \absolute_path3 as a2;
    use complex\path3 as a3;
    use \absolute\complex\path32 as a4;
    
    
    function y(
    one_identifier $a, 
    absolute_path $ab, 
    path $ac, 
    path2 $ad, 

    a1 $ae, 
    \a1 $af, 
    one_identifier3 $ag, 

    a2 $ah, 
    a3 $ai, 
    a4 $aj) {}

}