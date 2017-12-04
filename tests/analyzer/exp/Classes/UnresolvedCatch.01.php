<?php

$expected     = array('catch (LocalNonException $e) { /**/ } ',
                      'catch (OutOfBoundsException $e) { /**/ } ',
                      'catch (UndefinedClass $e) { /**/ } ',
                      'catch (Exception $e) { /**/ } ',
                     );

$expected_not = array('catch (LocalException $e) { /**/ } ',
                      'catch (LocalSubException $e) { /**/ } ',
                      'catch (LocalSubSubException $e) { /**/ } ',
                      'catch (\\Exception $e) { /**/ } ',
                      'catch (\\OutOfBoundsException $e) { /**/ } ',
                     );

?>