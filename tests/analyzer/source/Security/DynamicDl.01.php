<?php

    // dynamically loading a library
	dl($library. PHP_SHLIB_SUFFIX);

    // dynamically loading ext/vips
	dl('vips.' . PHP_SHLIB_SUFFIX);

?>