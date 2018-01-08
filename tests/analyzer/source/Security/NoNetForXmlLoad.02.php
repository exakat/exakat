<?php

new SimpleXml($uri); // No options, so default options

new SimpleXml($uri, LIBXML_NONET) ;

new SimpleXml($uri, LIBXML_NOENT) ;
new SimpleXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING) ;
new SimpleXml($uri, LIBXML_NOENT) ;
new SimpleXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | B | C) ;
new SimpleXml($uri, LIBXML_NOENT | LIBXML_NOERROR | LIBXML_NOWARNING | A | LIBXML_NONET | C) ;

new SimpleXml($uri, $options);
new SimpleXml($uri, 33);

?>