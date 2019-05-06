<?php

const DIR = '';
const DIR_FULL = '';

class x {
    const DIR = '';
}

include_once DIR.'/include.php' ;
include_once DIR.'include.php' ;

include_once DIR.'/INCLUDE.php' ;
include_once \DIR_FULL.'/INCLUDE.php' ;
include_once x::DIR.'/INCLUDE.php' ;
include_once DIR.'INCLUDE.php' ;

include_once DIR.'/inexistant.php' ;
include_once \DIR_FULL.'/inexistant.php' ;
include_once x::DIR.'/inexistant.php' ;


include_once DIR.'/inc/include.php' ;
include_once DIR.'/INC/include.php' ;
include_once DIR.'/inc/INCLUDE.php' ;
include_once DIR.'/INC/INCLUDE.php' ;

include_once \DIR_FULL.'/inc/include.php' ;
include_once \DIR_FULL.'/INC/include.php' ;
include_once \DIR_FULL.'/inc/INCLUDE.php' ;
include_once \DIR_FULL.'/INC/INCLUDE.php' ;

include_once x::DIR.'/inc/include.php' ;
include_once x::DIR.'/INC/include.php' ;
include_once x::DIR.'/inc/INCLUDE.php' ;
include_once x::DIR.'/INC/INCLUDE.php' ;

?>