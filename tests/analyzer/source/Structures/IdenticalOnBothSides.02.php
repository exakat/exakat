<?php


// Different
(strpos($this->_format, 'Y') !== false) or (strpos($this->_format, 'y') !== false);

// Identical
(strpos($this->_format, 'Y') !== false) or (strpos($this->_format, 'Y') !== false);

// different
(isset($headers['X-Requested-With']) && ($headers['X-Requested-With'] == 'XMLHttpRequest')) || (isset($headers['x-requested-with']) && ($headers['x-requested-with'] == 'XMLHttpRequest'));
?>