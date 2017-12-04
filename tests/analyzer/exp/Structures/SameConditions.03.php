<?php

$expected     = array('if($a3) { /**/ } elseif($a1) { /**/ } elseif($a2) { /**/ } elseif($a3) { /**/ } else { /**/ } ',
                      'if($a2) { /**/ } elseif($a1) { /**/ } elseif($a2) { /**/ } else { /**/ } ',
                      'if($a10) { /**/ } elseif($a1) { /**/ } elseif($a2) { /**/ } elseif($a3) { /**/ } elseif($a4) { /**/ } elseif($a5) { /**/ } elseif($a6) { /**/ } elseif($a7) { /**/ } elseif($a8) { /**/ } elseif($a9) { /**/ } elseif($a10) { /**/ } else { /**/ } ',
                      'if($a) { /**/ } elseif($a) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if ($a) { /**/ } elseif ($a1) { /**/ } else { /**/ } ',
                      'if ($b) { /**/ } else { /**/ } ',
                     );

?>