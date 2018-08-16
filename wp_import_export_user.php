<?php
/*
Plugin Name: Import/Export User with meta
Plugin URI: 
Description: Export Users data and metadata to a csv file.
Version: 1.0.0
Author: Rey Den Nalasa
Author URI: reydennalasa.com
License: GPL2
Text Domain: import-export-to-csv
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
//* Defined constant
define( 'WPIE_TEXTDOMAIN', 'import-export-to-csv' );
define( 'WPIE_VERSION', '1.0.0' );
define( 'WPIE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'WPIE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

function classAutoLoader($class){
	$needle   = "wpie";
	if( strpos( $class, $needle ) !== false) {
	 	return;
	}

	$class    = strtolower($class);
	$the_path = WPIE_PLUGIN_PATH."classes/{$class}.php";
	if(file_exists($the_path)){
		require_once($the_path);
	}
}

spl_autoload_register('classAutoLoader');

$wpie_export = new WPIE_Export();
$wpie_import = new WPIE_Import();