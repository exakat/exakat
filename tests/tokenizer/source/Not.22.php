<?php
		if($this->B == $b) {
			$this->C = $this->C & ~E::F;
			$this->G = 1;
		}
		elseif($this->H == $b) {
			$this->C = $this->C & ~E::L;
			$this->M = 1;
		}
		else throw new N('O');
		
		return P;
