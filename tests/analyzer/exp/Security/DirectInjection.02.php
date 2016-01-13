<?php

$expected     = array('print($_GET[\'DOCUMENT_ROOT\'])',
                      'print($_SERVER[\'UNKNOWN_INDEX\'])',
                      '\'ls \' . $_POST[\'DOCUMENT_ROOT\']',
                      '\'ls \' . $_SERVER[\'UNKNOWN_INDEX\'][\'a2\']',
                      '\'ls \' . $_POST[\'DOCUMENT_ROOT\'][\'a4\']',
                      '\'ls \' . $_GET[\'DOCUMENT_ROOT\']',
                      '\'ls \' . $_SERVER[\'UNKNOWN_INDEX\']',
                      '\'ls \' . $_SERVER[\'DOCUMENT_ROOT\'][\'a1\']',
                      '\'ls \' . $_GET[\'DOCUMENT_ROOT\'][\'a3\']',
                      '\'ls \' . $_SERVER[\'DOCUMENT_ROOT\']',
                      '"$_SERVER[\'UNKNOWN_INDEX\']"',
                      '"$_SERVER[\'DOCUMENT_ROOT\']"',
                      );

$expected_not = array();

?>