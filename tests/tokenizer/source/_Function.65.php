<?php 
    class B {
        function print(C $b, D $c = null) {
            print E('F', $c);
            print $b . 'G';
        }
    }
