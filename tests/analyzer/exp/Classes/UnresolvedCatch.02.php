<?php

$expected     = array('catch (\\MyException $e) { /**/ } ',
                     );

$expected_not = array('catch (\\Throwable $e) { /**/ } ',
                      'catch (Throwable $e) { /**/ } ',
                      'catch (\\Exception $e) { /**/ } ',
                     );

?>