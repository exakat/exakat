<?php

namespace Exceptions;

class TooManyLevelInsideAStringException extends \Exception {
    public function __construct($message = '', $code = 0, Exception $previous = null) {
        
        parent::__construct("Too many levels inside the string : won't load.\n", $code, $previous);
    }
}

?>