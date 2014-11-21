<?php

class Phpexec {
    private $phpexec = 'php';
    private $tokens  = array();
    private $config = array();
    
    public function __construct($phpversion) {
        $phpversion3 = substr($phpversion, 0, 3);
        switch($phpversion3) {
            case '5.2' : 
                $this->phpexec = 'php52';
                break 1;

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

            case '7.0' : 
                $this->phpexec = 'php70';
                break 1;

            default: 
                $this->phpexec = 'php';
        }    


        // prepare the configuration
        $res = shell_exec($this->phpexec.' -i');
        preg_match('/short_open_tag => (\w+) => (\w+)/', $res, $r);
        $this->config['short_open_tag'] = $r[2] == 'On';

        shell_exec($this->phpexec.' -r "print \'<?php \\$this->tokens = \'; \\$x = get_defined_constants(true); var_export(array_flip(\\$x[\'tokenizer\'])); print \';  ?>\';" > /tmp/tokennames.php');
        include('/tmp/tokennames.php');
        unlink('/tmp/tokennames.php');

        // prepare the tokens
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
        shell_exec($this->phpexec.' -r "print \'<?php \\$tokens = \'; var_export(token_get_all(file_get_contents(\''.str_replace("\$", "\\\$", $file).'\'))); print \'; ?>\';" > '.$tmpFile);
        include($tmpFile);
        unlink($tmpFile);
        
        return $tokens;
    }

    public function countTokenFromFile($file) {
        $tmpFile = tempnam("/tmp", "Phpexec");
        $res = shell_exec($this->phpexec.' -r "print count(token_get_all(file_get_contents(\''.str_replace("\$", "\\\$", $file).'\'))); ?>" ');
        
        return (int) $res;
    }
    
    public function getExec() {
        return $this->phpexec;
    }

    public function compile($file) {
        $shell = shell_exec($this->phpexec.' -l '.escapeshellarg($file).' 2>&1');
        $shell = preg_replace("/(Strict Standards|PHP Warning|PHP Strict Standards): .*?\n/i", '', $shell);
        $shell = trim($shell);

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

    public function getConfiguration($name = null) {
        if ($name === null) {
            return $this->config;
        } elseif (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return $this->config;
        }
    }
}

?>