<?php
	function a(&$a)
	{
        $a->b = 2 + $b->c;
	}

	function b(&$b)
	{
        $a->b = 2 + $b->c;

	}

	function c(&$c)
	{
        $c->d = count($c);
	}
