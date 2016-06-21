<?php
foreach($a as $b) {
switch($type) {
		case 'A': $b = &$c; break;
		case 'B': $b = &$d; break 1;
		case 'C': $b = &$d; break 2;
		default:
            $b = @$e;
			break;
	}
}
?>