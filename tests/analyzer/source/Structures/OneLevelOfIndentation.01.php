<?php

class foo {
    function multipleLevels($array) {
        $return = array();
        foreach($array as $b) {

            // This is a second level of indentation
            if ($this->check($b)) { continue; }
            $return[] = $b;
        }
        return $return;
    }

    function oneLevel($array) {
        $return = array_filter($array, array($this, 'check'));
        return $return;
    }

}

?>
