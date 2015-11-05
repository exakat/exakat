<?php

$expected     = array('fann_train_on_file($ann, $filename, $max_epochs, $epochs_between_reports, $desired_error)', 
                      'fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC)', 
                      'fann_destroy($ann)', 
                      'fann_save($ann, dirname(__FILE__) . "/xor_float.net")', 
                      'fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC)', 
                      'fann_create_standard($num_layers, $num_input, $num_neurons_hidden, $num_output)'
);

$expected_not = array();

?>