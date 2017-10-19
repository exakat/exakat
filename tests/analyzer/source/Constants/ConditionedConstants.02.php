<?php

function unconditionalFunction() {}

define('normalConstant', 1);

if (!defined('x1')) { 
    define('conditionedByX1', 1);
}

if (!defined('x1')) { 
    if (!defined('x2')) { 
        define('conditionedByX12', 1);
    }
}

if (!defined('x1')) { 
    if (!defined('x2')) { 
        if (!defined('x3')) { 
            define('conditionedByX123', 1);
        }
    }
}

class z {
function envelope() {
    if (!defined('Y')) { 
        define('conditionedByY', 1);
    }
}
}

?>
