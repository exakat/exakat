<?php

# Create our gearman client
$gmclient= new GearmanClient(); 

# add the default job server
$gmclient->addServer(); 

# set a function to be called when the work is complete
$gmclient->setCompleteCallback("complete"); 

# add a task to perform the "reverse" function on the string "Hello World!"
$gmclient->addTask("reverse", "Hello World!", null, "1"); 

# add another task to perform the "reverse" function on the string "!dlroW olleH"
$gmclient->addTask("reverse", "!dlroW olleH", null, "2"); 

# run the tasks
$gmclient->runTasks(); 

function complete($task) 
{ 
  print "COMPLETE: " . $task->unique() . ", " . $task->data() . "\n"; 
}

?>