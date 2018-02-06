<?PHP
//a unix timestamp
$date    = "1094095513"; 

//some data to be included
$books   = array('programming'   => array('php','perl','java'),
                'markup'        => array('UML','XML','HTML')
                );

//stick data to an array to iterate over
$data_to_serialize = array($date,$books);

//create the packet
$packet = wddx_packet_start("SOME DATA ARRAY");

//loop through the data
foreach($data_to_serialize as $key => $data)
{
  //create a var whith the name of the content of $key
  $$key = $data;
  wddx_add_vars($packet,$key);
}

echo wddx_packet_end($packet);
echo someClass::wddx_packet_end();

?>