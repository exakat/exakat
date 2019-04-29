<?php

class foo {
    function multipleLevels($array) {
        $return = array();
        switch($array) {
            case 'b': 
                // This is a second level of indentation
                if ($this->check($b)) { break; }
                $return[] = $b;
            default : 
                $return[] = 'b';
        }
        return $return;
    }

    function multipleLevels2Switches($array) {
        $return = array();
        switch($array) {
            case 'b': 
                // This is a second level of indentation
            switch($b) {
                case 'b': 
                    // This is a second level of indentation
                    if ($this->check($b)) { break; }
                    $return[] = $b;
                default : 
                    $return[] = 'b';
                }
                $return[] = $b;
            default : 
                $return[] = 'b';
        }
        return $return;
    }
    
    function oneLevel($array) {
        switch($array) {
            case 'b': 
                // This is a second level of indentation
                $return[] = $b;
            default : 
                $return[] = 'b';
        }
    }

}

?>
