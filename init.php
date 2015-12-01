<?php
/*
Plugin Name: Code Manager
Plugin URI: http://www.relativelystatic.com
Description: Allows adding and managing of source code to a Wordpress website
Version: 1.0
Author: Charles Hriczko
Author URI: http://www.relativelystatic.com
License: GPLv2
*/
require_once('lib/constants.php');
require_once('lib/code_manager.class.php');

//Create the database table if it does not exist
function code_manager_activate(){
				global $wpdb;
}
register_activation_hook(__FILE__, 'code_manager_activate');

//Instantiate our class
$code_manager = new Code_Manager();
?>