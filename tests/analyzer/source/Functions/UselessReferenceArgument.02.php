<?php
	function a(&$a)
	{
        $a[1] = 2 + $b[3];
        
        $c[3] = count($c);
	}

	function b(&$b)
	{
        $a[1] = 2 + $b[3];
        
        $c[3] = count($c);
	}

	function c(&$c)
	{
        $c[3] = count($c);
	}
