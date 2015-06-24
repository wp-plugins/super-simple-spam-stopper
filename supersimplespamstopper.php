<?php
/**
 * Plugin Name: Super Simple SPAM Stopper
 * Plugin URI: http://bengribaudo.com/programming/wordpress-plugins/supersimplespamstopper/
 * Description: Attempts to prevent automated SPAM by requiring visitors to answer a question of your choosing in order for their comment to be accepted.
 * Version: 1.0.0
 * Author: Ben Gribaudo, LLC
 * Author URI: http://bengribaudo.com/
 * License: BSD_3Clause
 * License URI: http://directory.fsf.org/wiki/License:BSD_3Clause
 * Copyright: (c) 2015 Ben Gribaudo, LLC
 */
defined( 'ABSPATH' ) or die;

define( 'SSSPAMSTOPPER_OPTIONS_PREFIX', 'supersimplespamstopper-' );
define( 'SSSPAMSTOPPER_PLUGIN_DATAFILE',  __FILE__);

if ( is_admin() ) {
	require_once( 'class-supersimplespamstopper-admin.php' );
	new SuperSimpleSpamStopper_Admin();
}
 
require_once( 'class-supersimplespamstopper.php' );
new SuperSimpleSpamStopper();

