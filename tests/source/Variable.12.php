<?php
	$result = <<<EOF

#Allow smarty {php} tags?  These could be dangerous if you don't trust your users.
\$config['use_smarty_php_tags'] = ${$config['use_smarty_php_tags']?'true':'false'};

#Automatically assign alias based on page title?
\$config['auto_alias_content'] = ${$config['auto_alias_content']?'true':'false'};

EOF;

?>