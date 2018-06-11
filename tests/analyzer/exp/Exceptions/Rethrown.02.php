<?php

$expected     = array('try { /**/ } catch (NakedException $e) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (SecondException $e) { /**/ } ',
                     );

?>