<?php

const A = 3;
const B = 456789;
echo namespace \A;

namespace \B;

print strlen(namespace \B);

print 'ko';

echo __NAMESPACE__;

?>