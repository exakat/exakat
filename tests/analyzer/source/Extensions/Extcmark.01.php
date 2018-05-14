<?php
$text = new CommonMark\Node\Text;
$text->literal = "Hello World";
$document = new CommonMark\Node\Document;
$document->appendChild(
	(new CommonMark\Node\Paragraph)
		->appendChild($text));
echo CommonMark\Render\HTML($document);

HTML();
?>
