<?php

class Variable extends Token {
    function check() {
        $result = Token::query("g.V.has('token','T_VARIABLE').each{ it.setProperty('class','Variable') }");
        
        return true;
    }
}

?>