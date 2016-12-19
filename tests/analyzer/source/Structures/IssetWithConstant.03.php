<?php
        if (!isset($a->b[$c])) {
            throw new Exception();
        }

        if (!isset($a::b[$c])) {
            throw new Exception();
        }
