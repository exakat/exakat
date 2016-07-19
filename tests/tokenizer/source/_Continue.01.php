<?php

foreach($a as $b) {
	continue;
}

foreach($a2 as $b) {
	continue 1;
}

foreach($a3 as $b) {
    foreach($a as $b) {
	    continue 2;
	}
}

?>