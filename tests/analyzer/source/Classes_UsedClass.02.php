<?php

class Unused {}

class UsedInString {}
class UsedInStringLowercase {}

class UsedInArrayWithArray {}
class UsedInArrayWithArrayLowercase {}

class UsedInArrayWithBracket {}
class UsedInArrayWithBracketLowercase {}

array_map('UsedInString', range(1,3));
array_map('usedinstringlowercase', range(1,4));

array_map(array('UsedInArrayWithArray', 'method'), array());
array_map(array('usedinarraywitharraylowercase', 'method4'), array());

array_filter(range(1,10), ['UsedInArrayWithBracket', 'method2']);
array_filter(range(1,10), ['usedinarraywithbracketlowercase', 'method3']);

?>