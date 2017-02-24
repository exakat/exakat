<?php

	function C1(callable $d1, callable $e = null, callable $e2 = null, callable $e3)
	{
		$this->F($e);
		StaticCall::F2($e2);
	}

	function C2(callable $d2, callable $e = null, callable $e2 = null, callable $e3)
	{
		$this->F($e);
		StaticCall::F2($e2);
	}

	function C3(callable $d3, callable $e = null, callable $e2 = null, callable $e3)
	{
		$d3($e);
		$e3($e2);
	}

?>