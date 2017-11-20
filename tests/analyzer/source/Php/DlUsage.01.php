<?php

    // dynamically loading ext/vips
	dl('vips.' . PHP_SHLIB_SUFFIX);
	
	C::vl('something');

?>