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

class Cornac_Auditeur_Analyzer_Inventaire extends Cornac_Auditeur_Analyzer {
	
    function dependsOn() {
        return array(
"Classes_Constants",
"Classes_Definitions",
"Classes_Interfaces",
"Classes_MethodsDefinition",
"Classes_MethodsSpecial",
"Classes_Properties",
"Constants_Definitions",
"Functions_Definitions",
"Php_Globals",
"Php_References",
"Php_Modules",
"Sf_Dependencies",
"Structures_FluentInterface",
"Variables_Gpc",
"Variables_Names",
"Variables_Session",
"Variables_Variables",
"Zf_Dependencies",
"Php_Phpinfo",
"Php_Reflection",
"Php_Config",
"Php_Envvar",
        );
    }
    
    function analyse() {
        return true;
    }

}

?>