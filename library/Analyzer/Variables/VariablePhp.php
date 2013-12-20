<?php

namespace Analyzer\Variables;

use Analyzer;

class VariablePhp extends Analyzer\Analyzer {
    
    function analyze() {
        $this->atomIs("Variable")
             ->code(array('$_GET','$_POST','$_COOKIE','$_SERVER','_FILES','$_REQUEST','$_SESSION','$_ENV',
	                        '$PHP_SELF','$HTTP_RAW_POST_DATA',
	                        '$HTTP_GET_VARS','$HTTP_POST_VARS',
	                        '$GLOBALS'), true);
    }
}

?>