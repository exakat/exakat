<?php
  $xslt = new XSLTProcessor();
  $xslt->importStylesheet(new SimpleXMLElement($xslt_string));
  echo $xslt->transformToXml(new SimpleXMLElement($xml_string));
?>