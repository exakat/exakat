<?php

function unconditionalFunction() {}

if (!defined('x1')) { 
    function conditionedByX1() {}
}

if (!defined('x1')) { 
    if (!defined('x2')) { 
        function conditionedByX12() {}
    }
}

if (!defined('x1')) { 
    if (!defined('x2')) { 
        if (!defined('x3')) { 
            function conditionedByX123() {}
        }
    }
}

class z {
    function envelope() {
        if (!defined('Y')) { 
            function conditionedByY() {}
        }

        if (!defined('YClosure')) { 
            function ($closure) {};
        }
    }
}

?>
