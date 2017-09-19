<?php

$y = function ( $a, 
                $b = self::B,
                $c = self::C,
                $e = parent::D ) {};

$y = new class() {
            function b( $a2, 
                $b = self::B2,
                $c = self::C2,
                $e = parent::D2 ) {} };

?>