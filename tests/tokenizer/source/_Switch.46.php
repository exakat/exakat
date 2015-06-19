<?php
        switch($this->algorithm) {
        case self::ALGORITHM_NONE:
            ; // add nothing
            break;
            
        case self::ALGORITHM_DSA:
        case self::ALGORITHM_RSA:
            $data .= base64_decode($this->key);
            break;
            
        default:
            return null;
        }

?>