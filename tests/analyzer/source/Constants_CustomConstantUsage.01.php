<?php

const customConstant = 1;
define('customConstantByDefine', 2);
const customConstantUsedWithNsname = 1;
define('customConstantByDefineUsedWithNsname', 2);

const unusedCustomConstant = 3;
define('unusedCustomConstantByDefine', 4);

customConstant + customConstantByDefine;

\customConstantUsedWithNsname * \customConstantByDefineUsedWithNsname;

MYSQLI_TYPE_STRING + \MYSQLI_REFRESH_SLAVE;
?>