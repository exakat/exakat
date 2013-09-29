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


class Cornac_Auditeur_Analyzer_Zf_FormElementWithValidation extends Cornac_Auditeur_Analyzer {
	protected	$title = 'Zend_Form_Element* with validator';
	protected	$description = 'Zend_Form_Element* with validator';


// @doc if this analyzer is based on previous result, use this to make sure the results are here
	function dependsOn() {
	    return array('Zf_FormElementNew');
	}

	public function analyse() {
        $this->cleanReport();

        $validates = array('Zend_Validate_Alnum',
'Zend_Validate_Alpha',
'Zend_Validate_Barcode',
'Zend_Validate_Between',
'Zend_Validate_Callback',
'Zend_Validate_Ccnum',
'Zend_Validate_CreditCard',
'Zend_Validate_Date',
'Zend_Validate_Digits',
'Zend_Validate_EmailAddress',
'Zend_Validate_Exception',
'Zend_Validate_Float',
'Zend_Validate_GreaterThan',
'Zend_Validate_Hex',
'Zend_Validate_Hostname',
'Zend_Validate_Iban',
'Zend_Validate_Identical',
'Zend_Validate_InArray',
'Zend_Validate_Int',
'Zend_Validate_Ip',
'Zend_Validate_Isbn',
'Zend_Validate_LessThan',
'Zend_Validate_NotEmpty',
'Zend_Validate_PostCode',
'Zend_Validate_Regex',
'Zend_Validate_StringLength',
);
        $in = $this->makeIn($validates);

// @doc spot direct usage of Zend_Validate's
$query = <<<SQL
SELECT NULL, TR1.file, TR1.element, TR1.token_id, '{$this->name}', 0 
FROM <report> TR1 
JOIN <tokens> T2     
    ON TR1.token_id = T2.id 
JOIN <tokens> T3     
    ON T3.left BETWEEN T2.left AND T2.right AND        
    T2.file = T3.file AND        
    T3.type = '_functionname_' AND        
    T3.code IN ($in)
WHERE TR1.module = 'Zf_FormElementNew'
SQL;
        $this->execQueryInsert('report', $query);

// @doc spot usage of derived classes
	    $query = <<<SQL
SELECT NULL, TR1.file, TR1.element, TR1.token_id, '{$this->name}', 0 
FROM <report> TR1 
JOIN <tokens> T2 
    ON TR1.token_id = T2.id 
JOIN <tokens> T3     
    ON T3.left BETWEEN T2.left AND T2.right AND
    T2.file = T3.file AND        
    T3.type = '_functionname_'  
JOIN <report> TR2     
    ON TR2.module = 'Zf_Validator' AND        
    T3.code = TR2.element 
WHERE TR1.module = 'Zf_FormElementNew'
SQL;
        $this->execQueryInsert('report', $query);

        return true;
	}
}

?>