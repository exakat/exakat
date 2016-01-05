<?php

static $inGlobalScope = 1;

class B {
    static $reallyUnused = 1, $reallyUnused2;
    
	function C(D $c, callable $d, callable $e = null)
	{
	    static $notAProperty2, $notAProperty3 = 3;
		$c == 'E' || $this == $c
			?
			$d()
			:
			$this->F(new G\H($c . 'E'), $d, $e)
		;

		return $this;
	}

	private static function J()
	{
		static $notAProperty = null;

		if (! $f)
		{
			$f = new D;
		}

		return $f;
	}
}
