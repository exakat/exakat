<?php

class Integer extends Token {
    function check() {
        $result = Token::query("g.V.has('token','T_LNUMBER').each{ it.setProperty('class','Integer') }");
        
        return true;
    }
}

?>