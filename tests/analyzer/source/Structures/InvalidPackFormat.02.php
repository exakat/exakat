<?php

unpack('',$b);
unpack('v',$b);
unpack('n v c*',$b);
unpack('nvc',$b);
unpack('n2v3c4',$b);
unpack('n2v*c4',$b);
unpack('n2v*c@',$b);
unpack('nvc*',$b);
unpack('Tvc*',$b);
unpack('nvc@',$b);
unpack('cchars/n',$b);
unpack('cchars /n',$b);
unpack('cnint',$b);
unpack('cchars/nint',$b);
unpack('c2chars/nint',$b);
unpack('c2chars/Tint',$b);

?>