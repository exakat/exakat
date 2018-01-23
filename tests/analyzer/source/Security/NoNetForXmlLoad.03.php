<?php

simplexml_load_string($string, 'asd'); // No options, so default options

simplexml_load_string($string, 'asd', LIBXML_NONET) ;

simplexml_load_string($string, 'asd', LIBXML_NOENT) ;
simplexml_load_string($string, 'asd', LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING) ;
simplexml_load_string($string, 'asd', LIBXML_NOENT) ;
simplexml_load_string($string, 'asd', LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | B | C) ;
simplexml_load_string($string, 'asd', LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | LIBXML_NONET | C) ;

simplexml_load_string($string, 'asd', $options);
simplexml_load_string($string, 'asd', 33);

?>