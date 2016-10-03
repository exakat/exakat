<?php

wp_create_nonce('forVerifyPost');
wp_create_nonce('forVerifyGet');
wp_create_nonce('forVerifyRequest');
wp_create_nonce('forAjax');
wp_create_nonce('unChecked');
wp_create_nonce('unCheckedAtWrongPosition');

check_ajax_referer( 'forAjax', 'security' );
wp_verify_nonce( $_POST['forVerifyPost'], 'security' );
wp_verify_nonce( $_GET['forVerifyGet'], 'security' );
wp_verify_nonce( $_REQUEST['forVerifyRequest'], 'security' );

check_ajax_referer( 'uncreated', 'unCheckedAtWrongPosition' );

?>