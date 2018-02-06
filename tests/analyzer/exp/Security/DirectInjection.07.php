<?php

$expected     = array('\'ls \' . ($_SERVER[\'UNKNOWN_INDEX\'][\'a2\'])',
                      '\'ls \' . ($_GET[\'INCOMING\'][\'a3\'])',
                      '\'ls \' . ($_POST[\'DOCUMENT_ROOT\'][\'a4\'])',
                      'print ((($_GET[\'DOCUMENT_ROOT\'][\'a3\'])))',
                      'print ((($_GET[\'DOCUMENT_RATE\'][\'a4\'])))',
                     );

$expected_not = array('\'ls \' . ($_SERVER[\'DOCUMENT_ROOT\'][3])',
                      '\'ls \' . ($_SERVER["DOCU{$M}MENT_ROOT"][3])',
                      'print ((($_GET[\'DOCUMENT_ROOT\'][\'a3\'])))',
                     );

?>