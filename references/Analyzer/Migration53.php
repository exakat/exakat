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

class Cornac_Auditeur_Analyzer_Migration53 extends Cornac_Auditeur_Analyzer {
	
    function dependsOn() {
        return array(
"Php_ObsoleteFunctionsIn53",
"Php_NewByReference",
"Php_SetLocaleWithString",
"Quality_MktimeIsdst",
"Functions_CallByReference",
"Classes_ToStringNoArg",
"Classes_MagicMethodWrongVisibility",
"Quality_IniSetObsolet53",
"Php_ObsoleteModulesIn53",
"Php_ReservedWords53",
"Php_Clearstatcache",
"Php_Php53NewFunctions",
"Php_Php53NewClasses",
"Php_Php53NewConstants",
// @todo cover all mention on http://php.net/manual/fr/migration53.php
        );
    }
    
    function analyse() {
        return true;
    }

}

?>