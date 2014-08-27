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
    
    
    print one_identifier::a;
    print absolute_path::a;
    print path::a;
    print path2::a;

    print a1::a;
    print \a1::a;
    print one_identifier3::a;

    print a2::a;
    print a3::a;
    print a4::a;

}