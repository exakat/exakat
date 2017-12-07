<?php

$expected     = array('if($a) { /**/ } elseif($b) { /**/ } elseif($a) { /**/ } else { /**/ } ',
                      'if($b3) { /**/ } else  /**/  ',
                      'if($a2) { /**/ } else  /**/  ',
                      'if($a5) { /**/ } else  /**/  ',
                      'if($a4) { /**/ } elseif($b4) { /**/ } else  /**/  ',
                      'if($b6) { /**/ } elseif($a6) { /**/ } else  /**/  ',
                      'if($a6) { /**/ } else  /**/  ',
                      'if($b6) { /**/ } elseif($b6) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if ($a0) { /**/ } elseif ($b0) { /**/ } elseif ($c0) { /**/ } else { /**/ }',
                     );

?>