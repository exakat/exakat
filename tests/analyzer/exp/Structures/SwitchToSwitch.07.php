<?php

$expected     = array( 'if($a === 1) { /**/ } elseif($a === 2) { /**/ } elseif($a === 3) { /**/ } else { /**/ } ',
                     );

$expected_not = array( 'if($o->a( )) { /**/ } elseif($a === 2) { /**/ } elseif(isset($a)) { /**/ } else { /**/ } ',
                       'if($a === 1) { /**/ } elseif($a === 2) { /**/ } elseif(isset($a)) { /**/ } else { /**/ } '
                     );

?>