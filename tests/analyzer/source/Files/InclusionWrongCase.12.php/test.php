<?php

// Actual tests, yielding values
const D = 'include.php';
const DE = 'INCLUDE.PHP';

include D;
include DE;

// Only checking that Gremlin query won't raise an exception
include dirname(dirname(DIR_SOME));
include dirname(dirname(A\DIR_SOME));
include dirname(dirname(__FILE__));

include 'a'.dirname(dirname(DIR_SOME));
include 'a'.dirname(dirname(A\DIR_SOME));
include 'a'.dirname(dirname(__FILE__));
include 'a'.'b';
include 'a'.B;
include 'a'.B\C;

include ('a'.dirname(dirname(DIR_SOME)));
include ('a'.dirname(dirname(A\DIR_SOME)));
include ('a'.dirname(dirname(__FILE__)));
include ('a'.'b');
include ('a'.B);
include ('a'.B\C);


include dirname(substr($tmp, 0, ($i + 1)));
include substr($tmp, 0, ($i + 1));
include 'a'.dirname(dirname(DIR_SOME));
include substr($tmp, 0, ($i + 1)) . "/main.inc.php";
include $a->template_dir . ($this->card ? $this->card . '_' : '') . $this->_cleanaction($action) . '.tpl.php';
include realpath(dirname(__FILE__)) . '/../main.inc.php';
include PHPExcel_Settings::getChartRendererPath( ) . 'jpgraph_radar.php';
include constant('TCPDF_PATH') . '/include/tcpdf_filters.php';


?>