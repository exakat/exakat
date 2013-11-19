<?php
		if ($a->b()) {
			declare (ticks = 1);

			while (true) {
				$this->b();
			}
		}
?>