<?php

foreach (['foo1'] as $thing) {
    switch ($foo) {
        case 'baz':
            switch ($x) {
                default : 
    			if ($thing === 'thing2') {
	    			continue 3;
		    	}
            }
		break;
	}
}

switch (['foo2']) {
    default: 
    switch ($foo) {
        case 'baz':
            switch ($x) {
                default : 
    			if ($thing === 'thing2') {
	    			continue 3;
		    	}
            }
		break;
	}
}

foreach (['foo3'] as $thing) {
    foreach ($foo as $f2) {
            switch ($x) {
                default : 
    			if ($thing === 'thing2') {
	    			continue 3;
		    	}
            }
	}
}

foreach (['foo4'] as $thing) {
    foreach ($foo as $f2) {
            foreach ($x as $y) {
    			if ($thing === 'thing2') {
	    			continue 3;
		    	}
            }
	}
}

?>