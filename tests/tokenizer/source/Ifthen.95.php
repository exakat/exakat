<?php
	if ($a)
		echo 'B', $b, 'C', !empty($c['D']['E']) ? 'F' : 'G' . $c['D']['I'], 'J', $c['D']['L'], 'M', $c['D']['L'], 'P', $b, 'Q';
	elseif ($d) $e++;

	if ($a) : 
		echo 'B', $b, 'C', !empty($c['D']['E']) ? 'F' : 'G' . $c['D']['I'], 'J', $c['D']['L'], 'M', $c['D']['L'], 'P', $b, 'Q';
	endif;
    