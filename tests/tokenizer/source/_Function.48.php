<?php

function a() {
		$a->b = function(){
			return array(
					array('c' => 'd', 'e' => 'f'),
				);
		};
		return 3;
}

?>