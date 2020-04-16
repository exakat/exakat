<?php

// for PHP 8.0
 __FUNCTION__[33];
 FUNCTION__[34];
 
 "dfdf"[33];
  "foo$bar"[0];
  
  // valid but useless
  "foo$bar"->baz();
  "foo$bar"::baz();
  
  echo self::CONST_BAR[0]::$baz; // Supported. Using const BOO = ['Bar'];

 echo self::CONST_BAR::$baz; // NOT supported. Using const BOO = 'Bar';


?>