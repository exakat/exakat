<?php

$doc = DOMDocument::loadXML('<node>old content</node>');

$newText = new DOMText('new content');

echo domcharacterdata::$property;

echo DOMNODELIST::XML_NOTATION_NODE;

new DOMNotReallyAClass();

?>