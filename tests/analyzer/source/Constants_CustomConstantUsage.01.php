<?php

const customConstant = 1;
define('customConstantByDefine', 2);

const unusedCustomConstant = 3;
define('unusedCustomConstantByDefine', 4);

customConstant + customConstantByDefine;

?>