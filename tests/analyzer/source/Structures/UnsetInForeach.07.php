<?php

        foreach($classes as $rowa) {
            unset($rowa['implements']);
        }

        foreach($classes as $rowo) {
            unset($rowo->uses);
        }

        foreach($classes as $rowv) {
            unset($rowv);
        }
