<?php

$expected     = array('catch (someRethrownException2 $e) { /**/ } ',
                      'catch (someRethrownException3 $e) { /**/ } ',
                      'catch (someRethrownException4 $e) { /**/ } ',
                     );

$expected_not = array('catch (someRethrownException $e) { /**/ } ',
                      'catch (someFixedException $e) { /**/ } ',
                     );

?>