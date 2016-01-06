<?php 
    class B {
        function exit(C $b, D $c = null) {
            print E('F', $c);
            print $b . 'G';
        }

        function die(C $b, D $c = null) {
            print E('F', $c);
            print $b . 'G';
        }
    }
