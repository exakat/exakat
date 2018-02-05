<?php

$expected     = array('print ($_GET[\'DOCUMENT_ROOT\'])',
                      'print ($_SERVER[\'UNKNOWN_INDEX\'])',
                      '\'ls \' . $_POST[\'DOCUMENT_ROOT\']',
                      '\'ls \' . $_SERVER[\'UNKNOWN_INDEX\'][\'a2\']',
                      '\'ls \' . $_POST[\'DOCUMENT_ROOT\'][\'a4\']',
                      '\'ls \' . $_GET[\'DOCUMENT_ROOT\']',
                      '\'ls \' . $_SERVER[\'UNKNOWN_INDEX\']',
                      '\'ls \' . $_GET[\'DOCUMENT_ROOT\'][\'a3\']',
                      '"{$_SERVER[\'UNKNOWN_INDEX\']}"',
                      'shell_exec($_POST[\'DOCUMENT_ROOT\'][\'a5\'])',
                     );

$expected_not = array('"$_SERVER[\'DOCUMENT_ROOT\']"',
                      '\'ls \' . $_SERVER[\'DOCUMENT_ROOT\'][\'a1\']',
                      'print ($_SERVER[\'DOCUMENT_ROOT\'])',
                      '\'ls \' . $_SERVER[\'DOCUMENT_ROOT\']',
                     );

?>