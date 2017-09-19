<?php

use Parle\{Token, Lexer, LexerException};

/* name => id */
$token = array(
        'EOI' => 0,
        'COMMA' => 1,
        'CRLF' => 2,
        'DECIMAL' => 3,
);
/* id => name */
$token_rev = array_flip($token);

$lex = new Lexer;
$lex->push("[x2c]", $token['COMMA']);
$lex->push("[r][n]", $token['CRLF']);
$lex->push("[d]+", $token['DECIMAL']);
$lex->build();

$in = "0,1,2rn3,42,5rn6,77,8rn";

$lex->consume($in);

do {
        $lex->advance();
        $tok = $lex->getToken();

        if (Token::UNKNOWN == $tok->id) {
                throw new LexerException('Unknown token "'.$tok->value.'" at offset '.$tok->offset.'.');
        }

        echo 'TOKEN: ', $token_rev[$tok->id], PHP_EOL;
} while (Token::EOI != $tok->id);

?>