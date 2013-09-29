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

class Cornac_Auditeur_Analyzer_Structures_AffectationsVariables extends Cornac_Auditeur_AnalyzerAttributes {
	protected	$title = 'Variables affected';
	protected	$description = 'List of affected variables : somewhere, they do receive a value.';

	
	public function analyse() {
        $this->cleanReport();

// @note simple variables
        $this->backend->setAnalyzerName($this->name);
        $this->backend->type('affectation')
                      ->firstChild()
                      ->type('variable')
                      ->reportId();
                      
        $this->backend->run('attributes');

// @note array
        $this->backend->type('affectation')
                      ->firstChild()
                      ->type('_array')
                      ->firstChild()
                      ->reportId();
                      
        $this->backend->run('attributes');

// @note property
        $this->backend->type('affectation')
                      ->firstChild()
                      ->type('property')
                      ->reportId();
                      
        $this->backend->run('attributes');

// @note  static property
        $this->backend->type('affectation')
                      ->firstChild()
                      ->type('property_static')
                      ->getTaggedToken('property')
                      ->reportId();

                      
        $this->backend->run('attributes');

// @note list() case
        $this->backend->type('affectation')
                      ->firstChild()
                      ->type('functioncall')
                      ->code('list')
                      ->inToken()
                      ->type('variable')
                      ->reportId();
                      
        $this->backend->run('attributes');

// @note foreach() case
        $this->backend->type('_foreach')
                      ->getTaggedToken(array('value','key'))
                      ->reportId();
                      
        $this->backend->run('attributes');

        return true;
    }
}

?>