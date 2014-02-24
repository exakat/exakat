<?php

class Phpexec {
    private $phpexec = 'php';
    private $tokens = array();
    
    public function __construct($phpversion) {
        switch($phpversion) {
            case '5.3' : 
                $this->phpexec = 'php53';
                break 1;

            case '5.4' : 
                $this->phpexec = 'php54';
                break 1;

            case '5.5' : 
                $this->phpexec = 'php55';
                break 1;

            case '5.6' : 
                $this->phpexec = 'php56';
                break 1;

            default: 
//                print "Didn't understand '$phpversion'. Will run it with default 'php' exec, version ".phpversion()."\n";
                $this->phpexec = 'php';
        }    

        shell_exec($this->phpexec.' -r "print \'<?php \\$this->tokens = \'; \\$x = get_defined_constants(true); var_export(array_flip(\\$x[\'tokenizer\'])); print \';  ?>\';" > /tmp/tokennames.php');
        include('/tmp/tokennames.php');
        unlink('/tmp/tokennames.php');

        $tphp = array(";" => 'T_SEMICOLON',
                      "=" => 'T_EQUAL',
                      "+" => 'T_PLUS',
                      "-" => 'T_MINUS',
                      "*" => 'T_STAR',
                      "/" => 'T_SLASH',
                      "%" => 'T_PERCENTAGE',
                      "(" => 'T_OPEN_PARENTHESIS',
                      ")" => 'T_CLOSE_PARENTHESIS',
                      "!" => 'T_BANG',
                      "[" => 'T_OPEN_BRACKET',
                      "]" => 'T_CLOSE_BRACKET',
                      "{" => 'T_OPEN_CURLY',
                      "}" => 'T_CLOSE_CURLY',
                      '.' => 'T_DOT',
                      ',' => 'T_COMMA',
                      '@' => 'T_AT',
                      '?' => 'T_QUESTION',
                      ':' => 'T_COLON',
                      '>' => 'T_GREATER',
                      '<' => 'T_SMALLER',
                      '&' => 'T_AND',
                      '^' => 'T_OR',
                      '|' => 'T_XOR',
                      '&&' => 'T_ANDAND',
                      '||' => 'T_OROR',
                      '"' => 'T_QUOTE',
                      '"_CLOSE' => 'T_QUOTE_CLOSE',
                      '$' => 'T_DOLLAR',
                      '`' => 'T_SHELL_QUOTE',
                      '`_CLOSE' => 'T_SHELL_QUOTE_CLOSE',
                      '~' => 'T_TILDE',
                      );
            foreach($tphp as $k => $v) {
               $this->tokens[$k] = $v; 
            }
    }
    
    public function getTokenName($token) {
        return $this->tokens[$token];
    }
    
    public function getTokenFromFile($file) {
        $tmpFile = tempnam("/tmp", "Phpexec");
        shell_exec($this->phpexec.' -r "print \'<?php \\$tokens = \'; var_export(token_get_all(file_get_contents(\''.$file.'\'))); print \'; ?>\';" > '.$tmpFile);
        include($tmpFile);
        unlink($tmpFile);
        
        return $tokens;
    }
    
    public function getExec() {
        return $this->phpexec;
    }

    public function compile($file) {
        $shell = shell_exec($this->phpexec.' -l '.$file.' 2>&1');
        if (trim($shell) == 'No syntax errors detected in '.$file) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getWhiteCode() {
        return array(
            array_search('T_WHITESPACE', $this->tokens) => 1,
            array_search('T_DOC_COMMENT', $this->tokens) => 1,
            array_search('T_COMMENT', $this->tokens) => 1,
        );
    }
}

?>