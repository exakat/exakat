<?php

$expected     = array('if($a->status === self::UNAVAILABLE) { /**/ } elseif($b->status === self::UNCHECKED) { /**/ } ',
                      'if($a->status === self::UNAVAILABLE) { /**/ } elseif($or === self::UNCHECKED) { /**/ } ',
                     );

$expected_not = array('if($a->status === self::UNAVAILABLE) { /**/ } elseif($a->status === self::UNCHECKED) { /**/ } ',
                     );

?>