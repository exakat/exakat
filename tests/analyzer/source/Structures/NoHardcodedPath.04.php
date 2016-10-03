<?php

// Glob has wild cards, like *
glob("*/*/views/*.php");

// Glob has wild cards, like ?
glob('*/*/views/ab?.php');

// Glob has wild cards, like * ?
glob("/views/a*b?.php");

// Glob has wild cards, like * ?
glob('/views/$a/ab.php');

?>