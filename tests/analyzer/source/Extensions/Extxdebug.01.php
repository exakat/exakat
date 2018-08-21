<?php
    function fix_string($a)
    {
        echo "Called @ ".
            xdebug_call_file().
            ":".
            xdebug_call_line().
            " from ".
            xdebug_call_function();
    }

    $ret = fix_string(array('Derick'));
    S::xdebug_call_function(2);

?>