<?php
	function __static__construct()
	{
		// Should only be run once
		if (count(K::$load_paths) > 0) return ;
	}
