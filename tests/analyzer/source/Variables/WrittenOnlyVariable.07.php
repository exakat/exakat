<?php
	function b()
	{
		$a = 123;

        $b1 = '/c/'.$a;
	}


	function c()
	{
		$e = 123;

        $b2 = "/d/$e";
	}

	function f()
	{
		$i = 123;

        $b3 = <<<H
/d/$i
H;
	}
?>