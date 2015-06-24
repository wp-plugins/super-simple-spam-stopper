<?php
/*
Copyright (c) 2015 Ben Gribaudo, LLC <www.bengribaudo.com>
*/
defined( 'WP_UNINSTALL_PLUGIN' ) or die;


$prefix = 'supersimplespamstopper-';

delete_option( $prefix . 'question' );
delete_option( $prefix . 'answer' );
delete_option( $prefix . 'answer_case_sensitive' );
delete_option( $prefix . 'answer_preserve_whitespace' );
delete_option( $prefix . 'answer_regex' );