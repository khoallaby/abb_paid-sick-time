<?php

/**
 * Plugin Name: ABB - Paid sick time laws
 * Plugin URI: http://www.ABetterBalance.org
 * Description: Paid sick time laws
 * Version: 0.0.1
 * Author: Andy Nguyen
 * Author URI: http://www.andynguyen.net
 */


define( 'abb_pst_plugin_url', plugin_dir_url(__FILE__ ) );
define( 'abb_pst_plugin_path', plugin_dir_path(__FILE__ ) );
define( 'abb_pst_plugin_version', '1.0.0' );

# todo: build importer pls
require_once( dirname(__FILE__) . '/resources/controllers/Base.php');
require_once( dirname(__FILE__) . '/resources/controllers/Shortcodes.php');

require_once( dirname(__FILE__) . '/resources/controllers/Pdf.php');
require_once( dirname(__FILE__) . '/resources/controllers/CustomPostTypes.php');
require_once( dirname(__FILE__) . '/resources/controllers/PaidSickTime.php');
require_once( dirname(__FILE__) . '/resources/controllers/Locations.php');
require_once( dirname(__FILE__) . '/resources/controllers/Answers.php');
require_once( dirname(__FILE__) . '/resources/controllers/Questions.php');
require_once( dirname(__FILE__) . '/resources/controllers/Search.php');


function vard($s) {
    echo '<pre>';
    var_dump($s);
    echo '</pre>';
}