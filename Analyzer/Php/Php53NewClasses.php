<?php
/*
   +----------------------------------------------------------------------+
   | Cornac, PHP code inventory                                           |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010 - 2011                                            |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Damien Seguy <damien.seguy@gmail.com>                        |
   +----------------------------------------------------------------------+
 */

class Cornac_Auditeur_Analyzer_Php_Php53NewClasses extends Cornac_Auditeur_Analyzer {
	protected	$title = 'New classes in PHP 5.3';
	protected	$description = 'Spot classes names that will appear in PHP 5.3.';

// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array("Classes_News");
	}

	public function analyse() {
        $this->cleanReport();

        $in = array('DateInterval',
'DatePeriod',
'Phar',
'PharData',
'PharException',
'PharFileInfo',
'FilesystemIterator',
'GlobIterator',
'MultipleIterator',
'RecursiveTreeIterator',
'SplDoublyLinkedList',
'SplFixedArray',
'SplHeap',
'SplMaxHeap',
'SplMinHeap',
'SplPriorityQueue',
'SplQueue',
'SplStack',);

        $this->backend->setAnalyzerName($this->name);
        $this->backend->element($in)
                      ->reportCode('element')
                      ->module('Classes_News');
        $this->backend->run();

        return true;
	}
}

?>