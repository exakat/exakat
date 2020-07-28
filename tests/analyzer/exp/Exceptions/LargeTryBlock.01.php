<?php

$expected     = array('try { /**/ } catch (DatabaseException6 $e) { /**/ } ',
                     );

$expected_not = array('try { /**/ } catch (DatabaseException1 $e) { /**/ } ',
                      'try { /**/ } catch (DatabaseException5 $e) { /**/ } ',
                     );

?>