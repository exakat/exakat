<?php

namespace B\C{
    class D {
        const E = 1;
    }
}

namespace F\G\H {
    use B;
    
    echo B\C\D::E;
    echo B\C\D::Q;
}