<?php

namespace {
    class one_identifier {
        static public $a = 33;
    }
    class absolute_path {
        static public $a = 34;
    }
    class one_identifier3 {
        static public $a = 37;
    }

    class a1 {
        static public $a = 38;
    }
}

namespace complex {
    class path {
        static public $a = 35;
    }
}

namespace absolute\complex {
    class path2 {
        static public $a = 36;
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
    
    
    print one_identifier::$a;
    print absolute_path::$a;
    print path::$a;
    print path2::$a;

    print a1::$a;
    print \a1::$a;
    print one_identifier3::$a;

    print a2::$a;
    print a3::$a;
    print a4::$a;

}