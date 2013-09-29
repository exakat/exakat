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

class Cornac_Auditeur_Analyzer_Zf_Elements extends Cornac_Auditeur_Analyzer {
	protected	$title = 'ZF : non-validated ZF element';
	protected	$description = 'Form element that were not validated';

	
	public function analyse() {
        $this->cleanReport();
        
        $classes = array(
'Zend_Form_Element_Button',
'Zend_Form_Element_Captcha',
'Zend_Form_Element_Checkbox',
'Zend_Form_Element_Exception',
'Zend_Form_Element_File',
'Zend_Form_Element_Hash',
'Zend_Form_Element_Hidden',
'Zend_Form_Element_Image',
'Zend_Form_Element_Multi',
'Zend_Form_Element_MultiCheckbox',
'Zend_Form_Element_Multiselect',
'Zend_Form_Element_Password',
'Zend_Form_Element_Radio',
'Zend_Form_Element_Reset',
'Zend_Form_Element_Select',
'Zend_Form_Element_Submit',
'Zend_Form_Element_Text',
'Zend_Form_Element_Textarea',
'Zend_Form_Element_Xhtml',
);
        
        $in = join("', '", $classes);
	    $query = <<<SQL
SELECT T1.left, T1.right, T1.file , T1.id, T2.code
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file = T1.file AND T2.left BETWEEN T1.left AND T1.right 
WHERE T2.code in ('$in') AND 
      T1.type='affectation'
SQL;

    $res = $this->execQuery($query);
    
    while($row = $res->fetch()) {
        $left = $row['right'] + 1;
        $trouve = false;
        while(!$trouve) {
	        $query = <<<SQL
SELECT T1.left, T1.right, T1.file, SUM(if (T2.code='addElement', 1, 0)) AS addElement
FROM <tokens> T1
JOIN <tokens> T2
    ON T2.file=  T1.file AND
       T2.left BETWEEN T1.left AND T1.right
WHERE T1.left = $left AND 
      T1.file='{$row["file"]}'
GROUP BY T1.left, T1.right, T1.file
SQL;

            $res2 = $this->execQuery($query);
            $row2 = $res2->fetch();
            
            $trouve = $row2['addElement'] != 0;
            $left = $row2['right'] + 1;
        
        }
        
        $query = <<<SQL
SELECT sum(if (T1.code IN ('addValidator','addFilter'), 1, 0)) AS addValidator, T1.file 
FROM <tokens> T1 
WHERE file = '{$row['file']}' AND 
      left BETWEEN {$row['left']} AND {$row2['right']}
SQL;
        $res2 = $this->execQuery($query);
        $row2 = $res2->fetch();
        
	    $query = <<<SQL
INSERT INTO <report> VALUES 
    (0, '{$row2['file']}', '{$row['code']} : {$row2['addValidator']}' , {$row['id']}, '{$this->name}', 0 );
SQL;
        $this->execQuery($query);
        }
        
        return true;
	}
}

?>