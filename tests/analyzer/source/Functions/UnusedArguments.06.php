<?php
            function () use ($V) {
                $d = $V[1] + $b;
            };

            function () use ($W) {
                $c = $W + $a;
            };

            function () use ($X) {
                $e = $X->d + $c;
            };

            function ($a) use ($V) {
                ++$a;
            };

            function ($a) use ($W) {
                ++$a;
            };

            function ($a) use ($X) {
                ++$a;
            };
            
?>