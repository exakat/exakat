<?php
            if ($adodbrs->fetchMode == 'ADODB_FETCH_BOTH' || count($adodbrs->fields) == 2 * $adodbrs->FieldCount())
                for ($i = 0; $i < $numfields; $i++)
                    if ($adodbrs->fields[$i] === null)
                        $columns[$i] =& new xmlrpcval ('');
                    else
                        $columns[$i] =& xmlrpc_encode ($adodbrs->fields[$i]);
            else
                foreach ($adodbrs->fields as $val)
                    if ($val === null)
                        $columns[] =& new xmlrpcval ('');
                    else
                        $columns[] =& xmlrpc_encode ($val);

?>