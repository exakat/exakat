<?php

switch ($bar) {
    case 'baz':
		foreach (['bar'] as $thing) {
			if ($thing === 'thing2') {
				continue;
			}
		}
		break;
}

switch ($bar) {
    case 'baz':
		foreach (['bar'] as $thing) {
			if ($thing === 'thing2') {
				continue 2;
			}
		}
		break;
}

foreach (['foo2'] as $thing) {
    switch ($foo) {
        case 'baz':
			if ($thing === 'thing2') {
				continue 2;
			}
		break;
	}
}

foreach (['foo'] as $thing) {
    switch ($foo) {
        case 'baz':
			if ($thing === 'thing2') {
				continue;
			}
		break;
	}
}

?>