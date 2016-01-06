<?php 
    class B {
        function require(C $b, D $c = null) {
            print E('F', $c);
        }

        function require_once(C $b, D $c = null) {
            print $b . 'G';
        }

        function include(C $b) {
            print E('F', $c);
        }

        function include_once(C $b, D $c = null) {
            print $b . 'G';
        }
    }
