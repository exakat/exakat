<?php

        while(fgetcsv($fp, 1000, "\t", '"')) { $frels++; }
        while(a::b($fp, 1000, "\t", '"')) { $frels++; }
        while($c->d($fp, 1000, "\t", '"')) { $frels++; }

        while(strtolower('D')) { $frels++; }

?>