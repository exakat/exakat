<?php

		switch (B1)
		{
			case $this->C === 'D':
				$this->C = 'F' . $this->C;
				break;

			case $this->C === $b->C:
				$this->C = 'F';
				break;

			case $this->L($b):
				$this->C = 'N' . O(P($this->C, R($b->C)), 'D');
				break;

			default:
				$c = 'U';

				while ($this->V($b))
				{
					$c .= 'W';

					$b = $b->X();
				}

				$this->C = static::Z($c) . 'D' . O(P($this->C, R($b->C)), 'D');
		}

		switch (B2)
		{
			case $this->C === 'D':
				$this->C = 'F' . $this->C;
				break;

			case $this->C === $b->C:
				$this->C = 'F';
				break;

			case $this->L($b):
				$this->C = 'N' . O(P($this->C, R($b->C)), 'D');
				break;

			case $this->C === 'D':
				$this->C = 'F' . $this->C;
				break;

			default:
				$c = 'U';

				while ($this->V($b))
				{
					$c .= 'W';

					$b = $b->X();
				}

				$this->C = static::Z($c) . 'D' . O(P($this->C, R($b->C)), 'D');
		}

?>
