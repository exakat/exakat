<?php

	function a ($m) {
		$a = array_merge(
			$b->get(null, $m),
			$b->get(null, "c/$dm"),
			$b->get(null, "c/$dc")
		);
		if (!empty($a)) {
			$b->del(
				array_column($a, 'e')
			);
		}
	}
	
	function b ($m2) {
		$a = array_merge(
			$b->get(null, $m2),
			$b->get(null, "c/$dm"),
			$b->get(null, "c/$dc")
		);
		if (!empty($a)) {
			$b->del(
				array_column($b, 'id')
			);
		}
	}

?>