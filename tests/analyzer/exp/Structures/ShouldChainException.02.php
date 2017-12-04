<?php

$expected     = array('catch (someRethrownException5 $e5) { /**/ } ',
                     );

$expected_not = array('catch (someRethrownException $e0) { /**/ } ',
                      'catch (someRethrownException1 $e1) { /**/ } ',
                      'catch (someRethrownException1 $e2) { /**/ } ',
                      'catch (someRethrownException1 $e3) { /**/ } ',
                      'catch (someRethrownException1 $e4) { /**/ } ',
                     );

?>